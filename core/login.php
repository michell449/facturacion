<?php
//core/login.php
//Funcionalidad de login de usuarios 
require_once __DIR__ . '/class/db.php';
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {

    $data = json_decode(file_get_contents('php://input'), true);
    $email = isset($data['email']) ? trim($data['email']) : '';
    $password = isset($data['password']) ? $data['password'] : '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Correo y contraseña son obligatorios.']);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT id_usuario, correo, contrasena, tipo_usuario, verificacion FROM usuarios WHERE correo = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['contrasena'])) {
        
        if ($user['verificacion'] == 0) {
            echo json_encode(['success' => false, 'message' => 'Tu cuenta no ha sido verificada. Revisa tu correo.']);
            exit;
        }

        $_SESSION['usuario_id'] = $user['id_usuario'];
        $_SESSION['correo'] = $user['correo'];
        $_SESSION['tipo_usuario'] = $user['tipo_usuario'];
        $_SESSION['loggedin'] = true;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Inicio de sesión exitoso.',
            'tipo_usuario' => $user['tipo_usuario'],
            'usuario_id' => $user['id_usuario']
        ]);

    }
    else {
        echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
}