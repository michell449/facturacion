<?php
// crear_tabla_cat_regimen.php
// Script para crear la tabla cat_regimen y poblarla con los datos vigentes del SAT
require_once __DIR__ . '/../class/db.php';

$db = new Database();
$conn = $db->getConnection();
if (!$conn) {
    die('Error de conexión PDO');
}

$sql = " CREATE TABLE IF NOT EXISTS `cat_regimen_fiscal` (
    `id_rf` INT(3) PRIMARY KEY AUTO_INCREMENT,
    `codigo` VARCHAR(5) NOT NULL,
    `descr` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $conn->exec($sql);
    echo "Tabla cat_regimen_fiscal creada correctamente.<br>";
} catch (PDOException $e) {
    echo "Error al crear la tabla cat_regimen_fiscal: " . $e->getMessage();
}

// insertar los datos del SAT

$regimenes = [
    ['601', 'REGIMEN GENERAL DE LEY PERSONAS MORALES'],
    ['602', 'REGIMEN SIMPLIFICADO DE LEY PERSONAS MORALES'],
    ['603', 'PERSONAS MORALES CON FINES NO LUCRATIVOS'],
    ['604', 'REGIMEN DE PEQUEÑOS CONTRIBUYENTES'],
    ['605', 'REGIMEN DE SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS'],
    ['606', 'REGIMEN DE ARRENDAMIENTO'],
    ['607', 'REGIMEN DE ENAJENACION O ADQUISICION DE BIENES'],
    ['608', 'REGIMEN DE LOS DEMAS INGRESOS'],
    ['609', 'REGIMEN DE CONSOLIDACION'],
    ['610', 'REGIMEN RESIDENTES EN EL EXTRANJERO SIN ESTABLECIMIENTO PERMANENTE EN MEXICO'],
    ['611', 'REGIMEN DE INGRESOS POR DIVIDENDOS (SOCIOS Y ACCIONISTAS)'],
    ['612', 'REGIMEN DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES'],
    ['613', 'REGIMEN INTERMEDIO DE LAS PERSONAS FISICAS CON ACTIVIDADES EMPRESARIALES'],
    ['614', 'REGIMEN DE LOS INGRESOS POR INTERESES'],
    ['615', 'REGIMEN DE LOS INGRESOS POR OBTENCION DE PREMIOS'],
    ['616', 'SIN OBLIGACIONES FISCALES'],
    ['617', 'PEMEX'],
    ['618', 'REGIMEN SIMPLIFICADO DE LEY PERSONAS FISICAS'],
    ['619', 'INGRESOS POR LA OBTENCION DE PRESTAMOS'],
    ['620', 'SOCIEDADES COOPERATIVAS DE PRODUCCION QUE OPTAN POR DIFERIR SUS INGRESOS'],
    ['621', 'REGIMEN DE INCORPORACION FISCAL'],
    ['622', 'REGIMEN DE ACTIVIDADES AGRICOLAS, GANADERAS, SILVICOLAS Y PESQUERAS PM'],
    ['623', 'REGIMEN DE OPCIONAL PARA GRUPOS DE SOCIEDADES'],
    ['624', 'REGIMEN DE LOS COORDINADOS'],
    ['625', 'REGIMEN DE LAS ACTIVIDADES EMPRESARIALES CON INGRESOS A TRAVES DE PLATAFORMAS TECNOLOGICAS'],
    ['626', 'REGIMEN SIMPLIFICADO DE CONFIANZA'],
];

// Preparar la consulta de inserción sin incluir la columna 'id_rf'
$stmt = $conn->prepare("INSERT INTO cat_regimen_fiscal (codigo, descr) VALUES (?, ?)");

try {
    foreach ($regimenes as $regimen) {
        $stmt->execute($regimen);
    }
    echo " Datos insertados correctamente en cat_regimen_fiscal.";
} catch (PDOException $e) {
    echo " Error al insertar datos en cat_regimen_fiscal: " . $e->getMessage();
}

$conn = null; // Cierra la conexión después de insertar los datos
?>
