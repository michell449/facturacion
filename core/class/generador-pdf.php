<?php
/**
 * Clase para generar PDFs de facturas usando mPDF y plantilla-factura.php
 */

require_once __DIR__ . '/../autoload-vendor.php';
require_once __DIR__ . '/db.php';

use Mpdf\Mpdf;

class PdfGenerator
{
    /**
     * Genera el PDF de una factura
     * 
     * @param int $idFactura ID de la factura
     * @param int $idUsuario ID del usuario (para validación)
     * @param bool $guardarEnDisco Si se debe guardar el PDF en disco
     * @param string $modo Modo de salida: 'I' (navegador), 'D' (descarga), 'F' (solo guardar)
     * @return string Ruta relativa del PDF guardado (si $guardarEnDisco es true)
     */
    public static function generar($idFactura, $idUsuario, $guardarEnDisco = true, $modo = 'I')
    {
        // Validar parámetros
        $idFactura = (int)$idFactura;
        $idUsuario = (int)$idUsuario;
        
        if ($idFactura <= 0) {
            throw new Exception('ID de factura inválido.');
        }
        
        // Conectar a BD
        $db = new Database();
        $conn = $db->getConnection();
        
        // Obtener datos de la factura
        $stmt = $conn->prepare("
            SELECT 
                f.*,
                e.rfc as rfc_emisor,
                e.nombre as nombre_emisor,
                e.razon_social as razon_social_emisor,
                e.direccion as direccion_emisor,
                e.colonia as colonia_emisor,
                e.cp as cp_emisor,
                e.reg_fiscal as regimen_fiscal_emisor,
                e.id_empresa
            FROM facturas f
            INNER JOIN empresas e ON f.id_empresa = e.id_empresa
            WHERE f.id_factura = ? AND f.id_usuario = ?
        ");
        $stmt->execute([$idFactura, $idUsuario]);
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$factura) {
            throw new Exception('Factura no encontrada o no tiene permisos para acceder a ella.');
        }
        
        // Validar que esté timbrada
        if ($factura['estatus'] !== 'timbrada') {
            throw new Exception('La factura debe estar timbrada para generar el PDF.');
        }
        
        $idSucursal = $factura['id_empresa'];
        
        // Obtener configuración
        $stmtConfig = $conn->prepare("
            SELECT * FROM config_facturas 
            WHERE id_usuario = ? AND id_sucursal = ?
            ORDER BY fecha_actualizacion DESC 
            LIMIT 1
        ");
        $stmtConfig->execute([$idUsuario, $idSucursal]);
        $config = $stmtConfig->fetch(PDO::FETCH_ASSOC);
        
        // Config por defecto si no existe
        if (!$config) {
            $config = [
                'color_primario' => '#0d6efd',
                'color_secundario' => '#6c757d',
                'tipo_letra' => 'Arial',
                'tamano_letra' => 10,
                'leyenda_factura' => 'Este documento es una representación impresa de un CFDI',
                'logo_url' => null,
                'serie_factura' => 'A',
                'moneda' => 'MXN'
            ];
        }
        
        // Obtener detalles/conceptos
        $stmtDetalles = $conn->prepare("
            SELECT * FROM facturas_detalles 
            WHERE id_factura = ?
            ORDER BY id_detalle ASC
        ");
        $stmtDetalles->execute([$idFactura]);
        $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);
        
        // Convertir a formato de conceptos
        $conceptos = [];
        foreach ($detalles as $detalle) {
            $conceptos[] = [
                'clave_producto' => $detalle['clave_prod_serv'] ?? '01010101',
                'cantidad' => $detalle['cantidad'] ?? 1,
                'unidad' => $detalle['clave_unidad'] ?? 'H87',
                'descripcion' => $detalle['descripcion'] ?? 'Producto/Servicio',
                'precio_unitario' => $detalle['valor_unitario'] ?? 0,
                'importe' => $detalle['importe'] ?? 0
            ];
        }
        
        // Si no hay detalles, crear uno genérico
        if (empty($conceptos)) {
            $conceptos = [[
                'clave_producto' => '01010101',
                'cantidad' => 1,
                'unidad' => 'H87',
                'descripcion' => 'Producto/Servicio',
                'precio_unitario' => $factura['subtotal'] ?? 0,
                'importe' => $factura['subtotal'] ?? 0
            ]];
        }
        
        // Capturar HTML de la plantilla
        ob_start();
        
        // Simular variables GET para la plantilla
        $_GET['id_factura'] = $idFactura;
        $_GET['id_sucursal'] = $idSucursal;
        $_GET['preview'] = 0;
        
        // Incluir plantilla
        require __DIR__ . '/../plantilla-factura.php';
        
        $html = ob_get_clean();
        
        // Crear instancia de mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'Letter',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 5,
            'margin_bottom' => 10,
            'margin_header' => 0,
            'margin_footer' => 5,
            'default_font' => $config['tipo_letra'] ?? 'Arial',
            'default_font_size' => ($config['tamano_letra'] ?? 10),
            'tempDir' => sys_get_temp_dir(),
            'setAutoTopMargin' => 'stretch',
            'autoMarginPadding' => 2
        ]);
        
        // Configurar metadatos
        $serie = $factura['serie_interno'] ?? 'A';
        $folio = str_pad($factura['folio_interno'] ?? 0, 6, '0', STR_PAD_LEFT);
        
        $mpdf->SetTitle('Factura ' . $serie . '-' . $folio);
        $mpdf->SetAuthor($factura['razon_social_emisor'] ?? $factura['nombre_emisor'] ?? 'Empresa');
        $mpdf->SetSubject('CFDI 4.0');
        $mpdf->SetKeywords('Factura, CFDI, ' . ($factura['uuid'] ?? ''));
        
        // Escribir HTML
        $mpdf->WriteHTML($html);
        
        // Nombre del archivo
        $nombreArchivo = "Factura_{$serie}{$folio}.pdf";
        
        // Guardar en disco si se solicita
        $rutaRelativa = null;
        if ($guardarEnDisco) {
            $dirPDF = __DIR__ . '/../../uploads/facturas_pdf/';
            if (!is_dir($dirPDF)) {
                mkdir($dirPDF, 0755, true);
            }
            
            $rutaPDF = $dirPDF . $nombreArchivo;
            $mpdf->Output($rutaPDF, 'F');
            
            // Actualizar BD con la ruta
            $rutaRelativa = 'uploads/facturas_pdf/' . $nombreArchivo;
            $stmtUpdate = $conn->prepare("UPDATE facturas SET pdf_path = ? WHERE id_factura = ?");
            $stmtUpdate->execute([$rutaRelativa, $idFactura]);
        }
        
        // Salida según el modo
        if ($modo === 'F') {
            // Solo guardar, no mostrar
            return $rutaRelativa;
        } else {
            // 'I' = mostrar en navegador, 'D' = forzar descarga
            $mpdf->Output($nombreArchivo, $modo);
            exit; // Importante: detener ejecución después de output
        }
    }
}
