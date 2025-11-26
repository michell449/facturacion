<?php
// Establecer código de respuesta HTTP 404
http_response_code(404);

// Determinar URL de regreso según el rol del usuario
$backUrl = 'index.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (function_exists('is_admin') && is_admin()) {
        $backUrl = 'panel?pg=inicio-admin';
    } else {
        $backUrl = 'panel?pg=inicio';
    }
}
?>

<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5 text-center">
                        <!-- Icono de error -->
                        <div class="mb-4">
                            <div class="mx-auto" style="width: 120px; height: 120px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-exclamation-triangle text-white" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        
                        <!-- Mensaje principal -->
                        <h1 class="display-4 fw-bold text-primary mb-3">404</h1>
                        <h2 class="h4 fw-bold text-dark mb-3">¡Oops! Página no encontrada</h2>
                        <p class="text-muted mb-5 fs-5">
                            La página que estás buscando no existe, ha sido movida o no está disponible en este momento.
                        </p>
                        
                        <!-- Botones de navegación -->
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="<?php echo $backUrl; ?>" class="btn btn-primary btn-lg rounded-3 px-4">
                                <i class="bi bi-house-door me-2"></i>
                                <?php echo (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) ? 'Panel Principal' : 'Inicio'; ?>
                            </a>
                            <button onclick="window.history.back()" class="btn btn-outline-secondary btn-lg rounded-3 px-4">
                                <i class="bi bi-arrow-left me-2"></i>Regresar
                            </button>
                        </div>
                        
                        <!-- Información adicional -->
                        <div class="mt-5 pt-4 border-top">
                            <small class="text-muted">
                                Si crees que esto es un error, por favor contacta al administrador del sistema.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>