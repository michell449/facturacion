<?php
/**
 * API para guardar la configuración de facturas
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/class/db.php';

header('Content-Type: application/json; charset=utf-8');

$respuesta = [
    'success' => false,
    'message' => 'Error desconocido.'
];

try {
    // Validar sesión
    $id_usuario = $_SESSION['usuario_id'] ?? $_SESSION['USR_ID'] ?? null;
    
    if (!$id_usuario) {
        throw new Exception('Sesión no válida o expirada.');
    }

    $db = new Database();
    $conn = $db->getConnection();

    // Obtener datos del POST
    $datos = [
        'nombre_empresa' => trim($_POST['nombreEmpresa'] ?? ''),
        'rfc_empresa' => strtoupper(trim($_POST['rfcEmpresa'] ?? '')),
        'regimen_fiscal' => $_POST['regimenFiscal'] ?? '',
        'cp_emisor' => trim($_POST['cpEmisor'] ?? ''),
        'direccion_empresa' => trim($_POST['direccionEmpresa'] ?? ''),
        
        'uso_cfdi' => $_POST['usoCfdi'] ?? 'G03',
        'forma_pago' => $_POST['formaPago'] ?? '04',
        'metodo_pago' => $_POST['metodoPagoDefault'] ?? 'PUE',
        'moneda' => $_POST['monedaDefault'] ?? 'MXN',
        'tipo_comprobante' => $_POST['tipoComprobante'] ?? 'I',
        'exportacion' => $_POST['exportacionDefault'] ?? '01',
        
        'color_primario' => $_POST['colorPrimario'] ?? '#0d6efd',
        'color_secundario' => $_POST['colorSecundario'] ?? '#6c757d',
        'tipo_letra' => $_POST['tipoLetra'] ?? 'Arial',
        'tamano_letra' => (int)($_POST['tamanoLetra'] ?? 12),
        
        'serie_factura' => strtoupper(trim($_POST['serieFactura'] ?? 'A')),
        'folio_inicial' => (int)($_POST['folioInicial'] ?? 1),
        
        'mostrar_logo' => isset($_POST['mostrarLogo']) ? (int)$_POST['mostrarLogo'] : 1,
        'mostrar_sello_digital' => isset($_POST['mostrarSelloDigital']) ? (int)$_POST['mostrarSelloDigital'] : 1,
        'mostrar_observaciones' => isset($_POST['mostrarObservaciones']) ? (int)$_POST['mostrarObservaciones'] : 1,
        
        'leyenda_factura' => trim($_POST['leyendaFactura'] ?? ''),
        'condiciones_pago' => trim($_POST['condicionesPagoTexto'] ?? ''),
        'observaciones_default' => trim($_POST['observacionesDefault'] ?? '')
    ];

    // Procesar logo si se subió
    $logo_url = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['logo'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        
        if (!in_array($extension, $extensiones_permitidas)) {
            throw new Exception('Formato de imagen no permitido. Use JPG, PNG, GIF, WEBP o SVG.');
        }
        
        if ($archivo['size'] > 2 * 1024 * 1024) { // 2MB máximo
            throw new Exception('El archivo es demasiado grande. Máximo 2MB.');
        }
        
        // Crear directorio si no existe
        $dir_logos = __DIR__ . '/../uploads/logos/';
        if (!is_dir($dir_logos)) {
            mkdir($dir_logos, 0755, true);
        }
        
        // Generar nombre único
        $nombre_archivo = 'logo_' . $id_usuario . '_' . time() . '.' . $extension;
        $ruta_destino = $dir_logos . $nombre_archivo;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            $logo_url = 'uploads/logos/' . $nombre_archivo;
        }
    }

    // Verificar si ya existe configuración para este usuario
    $stmt = $conn->prepare("SELECT id_config, logo_url FROM config_facturas WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $config_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($config_existente) {
        // Actualizar configuración existente
        $campos_update = [];
        $valores_update = [];
        
        foreach ($datos as $campo => $valor) {
            $campos_update[] = "$campo = ?";
            $valores_update[] = $valor;
        }
        
        // Agregar logo si se subió uno nuevo
        if ($logo_url) {
            $campos_update[] = "logo_url = ?";
            $valores_update[] = $logo_url;
            
            // Eliminar logo anterior si existe
            if ($config_existente['logo_url'] && file_exists(__DIR__ . '/../' . $config_existente['logo_url'])) {
                @unlink(__DIR__ . '/../' . $config_existente['logo_url']);
            }
        }
        
        $valores_update[] = $id_usuario;
        
        $sql = "UPDATE config_facturas SET " . implode(', ', $campos_update) . " WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute($valores_update);
        
        $respuesta['success'] = true;
        $respuesta['message'] = 'Configuración actualizada correctamente.';
        
    } else {
        // Insertar nueva configuración
        $datos['id_usuario'] = $id_usuario;
        if ($logo_url) {
            $datos['logo_url'] = $logo_url;
        }
        
        $campos = array_keys($datos);
        $placeholders = array_fill(0, count($campos), '?');
        
        $sql = "INSERT INTO config_facturas (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_values($datos));
        
        $respuesta['success'] = true;
        $respuesta['message'] = 'Configuración guardada correctamente.';
    }

} catch (Exception $e) {
    $respuesta['success'] = false;
    $respuesta['message'] = $e->getMessage();
}

echo json_encode($respuesta);
