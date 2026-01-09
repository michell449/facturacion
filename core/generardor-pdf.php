<?php
// class/generador-pdf.php

require_once __DIR__ . '/../core/autoload-vendor.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../config.php';

use Mpdf\Mpdf;

class PdfGenerator {

    /**
     * Genera el PDF y devuelve la ruta relativa y absoluta.
     */
    public static function generar($id_factura, $id_usuario, $guardarEnServidor = true, $descargar = false) {
        $db = new Database();
        $conn = $db->getConnection();

        // 1. Obtener datos de la factura (Tu consulta original optimizada)
        $stmt = $conn->prepare("
            SELECT f.*, 
                   e.razon_social as razon_social_emisor,
                   e.nombre as nombre_emisor,
                   e.rfc as rfc_emisor,
                   e.reg_fiscal as regimen_fiscal_emisor,
                   e.cp as cp_emisor,
                   e.direccion as direccion_emisor,
                   e.colonia as colonia_emisor,
                   e.id_empresa
            FROM facturas f
            JOIN empresas e ON f.id_empresa = e.id_empresa
            WHERE f.id_factura = ? AND f.id_usuario = ?
        ");
        $stmt->execute([$id_factura, $id_usuario]);
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$factura) {
            throw new Exception("Factura no encontrada o no pertenece al usuario.");
        }

        // 2. Verificar si ya existe el PDF generado para no volver a crearlo (Opcional, pero recomendado)
        $basePath = dirname(__DIR__); // Raíz del proyecto
        if (!empty($factura['pdf_path'])) {
            $rutaAbsolutaExistente = $basePath . '/' . $factura['pdf_path'];
            if (file_exists($rutaAbsolutaExistente) && !$descargar) {
                // Si solo queremos la ruta (ej. para email) y ya existe, retornamos esa
                return [
                    'success' => true,
                    'file' => basename($factura['pdf_path']),
                    'relative' => $factura['pdf_path'],
                    'absolute' => $rutaAbsolutaExistente
                ];
            }
        }

        // 3. Obtener configuración de la sucursal
        $stmtConfig = $conn->prepare("SELECT * FROM config_facturas WHERE id_sucursal = ? LIMIT 1");
        $stmtConfig->execute([$factura['id_empresa']]);
        $config = $stmtConfig->fetch(PDO::FETCH_ASSOC);

        if (!$config) {
            $config = [
                'color_primario' => '#0d6efd',
                'color_secundario' => '#6c757d',
                'tipo_letra' => 'Arial',
                'tamano_letra' => 10,
                'leyenda_factura' => 'Este documento es una representación impresa de un CFDI'
            ];
        }

        // 4. Obtener Conceptos
        $stmtDetalles = $conn->prepare("SELECT * FROM facturas_detalles WHERE id_factura = ?");
        $stmtDetalles->execute([$id_factura]);
        $conceptos = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

        // 5. Preparar Código QR (Versión corregida para evitar errores de SVG/Conexión)
        $qrImage = '';
        if (!empty($factura['uuid']) && $factura['uuid'] !== 'PENDIENTE') {
            $qrData = "https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?id=" . $factura['uuid'] . 
                      "&re=" . $factura['rfc_emisor'] . "&rr=" . $factura['rfc_receptor'] . 
                      "&tt=" . number_format($factura['total'], 6, '.', '') . "&fe=" . substr($factura['sello_cfdi'] ?? '', -8);
            
            // Intentar obtener imagen QR
            try {
                $qrUrl = "https://quickchart.io/qr?text=" . urlencode($qrData) . "&size=150&margin=1";
                $imgContent = @file_get_contents($qrUrl);
                if ($imgContent) {
                    $qrImage = 'data:image/png;base64,' . base64_encode($imgContent);
                }
            } catch (Exception $e) {
                $qrImage = ''; // Fallback silencioso
            }
        }

        // 6. Cargar Plantilla
        // Extraemos variables para que plantilla-factura.php las use
        $logoUrl = $config['logo_url'] ?? '';
        
        ob_start();
        // Asegúrate de que esta ruta sea correcta relativa a class/generador-pdf.php
        require __DIR__ . '/../plantilla-factura.php'; 
        $html = ob_get_clean();

        // 7. Configurar mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_top' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_bottom' => 5,
            'tempDir' => sys_get_temp_dir()
        ]);

        $serie = $factura['serie_interno'] ?? 'A';
        $folio = str_pad($factura['folio_interno'] ?? $id_factura, 6, '0', STR_PAD_LEFT);
        
        $mpdf->SetTitle("Factura $serie-$folio");
        $mpdf->WriteHTML($html);

        $nombreArchivo = "Factura_{$serie}{$folio}.pdf";
        $rutaRelativa = "/../uploads/facturas_pdf/$nombreArchivo";
        $rutaAbsoluta = $basePath . '/' . $rutaRelativa;

        // 8. Guardar en servidor
        if ($guardarEnServidor) {
            $dirUploads = $basePath . '/uploads/facturas_pdf/';
            if (!is_dir($dirUploads)) {
                mkdir($dirUploads, 0755, true);
            }
            
            $mpdf->Output($rutaAbsoluta, 'F'); // Guardar archivo

            // Actualizar BD siempre con la nueva ruta
            $stmtUpd = $conn->prepare("UPDATE facturas SET pdf_path = ? WHERE id_factura = ?");
            $stmtUpd->execute([$rutaRelativa, $id_factura]);
        }

        // 9. Salida al navegador (Download o Inline)
        if ($descargar === 'D' || $descargar === true) {
            $mpdf->Output($nombreArchivo, 'D'); // Descargar
        } elseif ($descargar === 'I') {
            $mpdf->Output($nombreArchivo, 'I'); // Ver en navegador
        }
        
        // Retornar rutas para que el correo pueda usarlas
        return [
            'success' => true,
            'file' => $nombreArchivo,
            'relative' => $rutaRelativa,
            'absolute' => $rutaAbsoluta
        ];
    }
}
?>