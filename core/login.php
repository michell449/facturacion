<?php
if (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/json');

require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth.php'; 

$response = [
    'success' => false,
    'message' => 'Solicitud no válida.',
    'redirect' => 'facturar-login'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);
    $email = isset($data['email']) ? trim($data['email']) : '';
    $password = isset($data['password']) ? $data['password'] : '';

    if (empty($email) || empty($password)) {
        $response['message'] = 'Correo y contraseña son obligatorios.';
    } else {

        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT id_usuario, correo, contrasena, tipo_usuario, verificacion, tipo_cliente FROM usuarios WHERE correo = ?");
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['contrasena'])) {
            
            if ((int)$user['verificacion'] === 0) {
                $response['message'] = 'Tu cuenta no ha sido verificada. Revisa tu correo.';
            } else {
                
                // Iniciar sesión y establecer variables
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $user['id_usuario'];
                $_SESSION['correo'] = $user['correo'];
                $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
                $_SESSION['loggedin'] = true;
                $_SESSION['tipo_cliente'] = $user['tipo_cliente'];
                $_SESSION['last_access'] = time();

                $response['success'] = true;
                $response['message'] = 'Inicio de sesión exitoso.';
                $response['tipo_usuario'] = $user['tipo_usuario'];
                $response['usuario_id'] = $user['id_usuario'];
                $response['tipo_cliente'] = $user['tipo_cliente'];

                if ($user['tipo_usuario'] === 'admin') {
                    $response['redirect'] = 'panel?pg=inicio-admin';
                } else {
                    // Verificar si ya tiene información fiscal registrada
                    $stmtFiscal = $conn->prepare("SELECT COUNT(*) as total FROM datos_fiscales_usuario WHERE id_usuario = ?");
                    $stmtFiscal->execute([$user['id_usuario']]);
                    $fiscalData = $stmtFiscal->fetch(PDO::FETCH_ASSOC);
                    
                    if ($fiscalData['total'] > 0) {
                        // Ya tiene información fiscal, ir al inicio normal
                        $response['redirect'] = 'panel?pg=inicio';
                    } else {
                        // Primera vez, ir a registro de información fiscal
                        $response['redirect'] = 'panel?pg=registro-info-usuarios';
                        $response['first_login'] = true;
                    }
                }
            }

        } else {
            $response['message'] = 'Correo o contraseña incorrectos.';
        }
    }
}

echo json_encode($response);
exit();