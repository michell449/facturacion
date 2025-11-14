<?php
//tabla catalogo uso cfdi 
require_once __DIR__ . '/../class/db.php';
include_once __DIR__ . '/../../config.php';

// Establece la conexión con la base de datos
$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    die("Error de conexión a la base de datos.");
}

// Crear la tabla cat_uso_cfdi
$sql = " DROP TABLE IF EXISTS `cat_uso_cfdi`;
CREATE TABLE IF NOT EXISTS `cat_uso_cfdi` (
    `id_uso_cfdi` INT(5) PRIMARY KEY AUTO_INCREMENT,
    `codigo` VARCHAR(4) NOT NULL,
    `descr` VARCHAR(75) NOT NULL,
    `p_fisica` TINYINT(1) NOT NULL,
    `p_moral` TINYINT(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $conn->exec($sql);
    echo "Tabla cat_uso_cfdi creada correctamente.<br>";
} catch (PDOException $e) {
    echo "Error al crear la tabla cat_uso_cfdi: " . $e->getMessage();
}

// Los datos a insertar
$usos_cfdi = [
    ['G03', 'Gastos en general', 1, 1],
    ['I01', 'Construcciones', 1, 1],
    ['I02', 'Mobilario y equipo de oficina por inversiones', 1, 1],
    ['I03', 'Equipo de transporte', 1, 1],
    ['I04', 'Equipo de computo y accesorios', 1, 1],
    ['I05', 'Dados, troqueles, moldes, matrices y herramental', 1, 1],
    ['I06', 'Comunicaciones telefonicas', 1, 1],
    ['I07', 'Comunicaciones satelitales', 1, 1],
    ['I08', 'Otra maquinaria y equipo', 1, 1],
    ['D01', 'Honorarios medicos, dentales y gastos hospitalarios.', 1, 0],
    ['D02', 'Gastos medicos por incapacidad o discapacidad.', 1, 0],
    ['D03', 'Gastos funerales.', 1, 0],
    ['D04', 'Donativos.', 1, 0],
    ['D05', 'Intereses reales efectivamente pagados por creditos hipotecarios (casa habitacion).', 1, 0],
    ['D06', 'Aportaciones voluntarias al SAR.', 1, 0],
    ['D07', 'Primas por seguros de gastos medicos.', 1, 0],
    ['D08', 'Gastos de transportacion escolar obligatoria.', 1, 0],
    ['D09', 'Depositos en cuentas para el ahorro, primas que tengan como base planes de pensiones.', 1, 0],
    ['D10', 'Pagos por servicios educativos (colegiaturas)', 1, 0],
    ['S01', 'Sin efectos fiscales', 1, 1],
    ['CP01', 'Pagos', 1, 1],
    ['CN01', 'Nomina', 1, 1]
];

// Preparar y ejecutar los inserts
$stmt = $conn->prepare("INSERT INTO cat_uso_cfdi (codigo, descr, p_fisica, p_moral) VALUES (?, ?, ?, ?)");

try {
    foreach ($usos_cfdi as $uso) {
        $stmt->execute($uso);
    }
    echo "Datos insertados correctamente en cat_uso_cfdi.";
} catch (PDOException $e) {
    echo "Error al insertar datos en cat_uso_cfdi: " . $e->getMessage();
}

$conn = null; 
