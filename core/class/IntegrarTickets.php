<?php

/**
 * ExternalIntegration.php
 * Se encarga de conectar con el sistema externo, descargar tickets
 * y guardarlos en la base de datos local para poder facturarlos.
 */

require_once __DIR__ . '/../autoload-vendor.php'; // Cargar Guzzle
require_once __DIR__ . '/db.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;


class IntegrarTickets
{
    private $conn;
    private $httpClient;
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        // Conexión BD Local
        $db = new Database();
        $this->conn = $db->getConnection();

        $this->baseUrl = 'https://api.sistema-externo.com/v1/';
        $this->apiKey = 'TU_TOKEN_DE_ACCESO';

        // Cliente HTTP (Guzzle)
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 10.0,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept'        => 'application/json',
            ]
        ]);
    }

    /**
     * 1. Consulta el ticket en el sistema externo
     */
    public function fetchExternalTicket($folioExterno)
    {
        try {
            // GET https://api.sistema-externo.com/v1/tickets/12345
            $response = $this->httpClient->request('GET', "tickets/{$folioExterno}");
            $body = $response->getBody();
            $data = json_decode($body, true);

            if (empty($data)) {
                return ['success' => false, 'message' => 'Respuesta vacía del sistema externo'];
            }

            return ['success' => true, 'data' => $data];
        } catch (RequestException $e) {
            return ['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()];
        }
    }


    /**
     * 2. Guarda el ticket externo en la BD local
     * @param array $externalData Datos tal cual vienen del otro sistema
     * @param int $idEmpresa ID de la empresa local que factura
     */
    public function importToLocalDB($externalData, $idEmpresa)
    {
        try {
            $this->conn->beginTransaction();

            // A. PREPARAR DATOS (Mapeo de campos Externo -> Local)
            // Asumimos que el JSON externo trae: { "folio": "A-100", "total": 500.00, "items": [...], "status": "pagado" }

            $folio = $externalData['folio'];

            // Validar si ya existe localmente para no duplicar
            $stmtCheck = $this->conn->prepare("SELECT id_ticket FROM tickets WHERE folio_ticket = ? AND id_empresa = ?");
            $stmtCheck->execute([$folio, $idEmpresa]);
            if ($stmtCheck->fetch()) {
                $this->conn->rollBack();
                return ['success' => false, 'message' => 'Este ticket ya fue importado anteriormente.'];
            }

            $fechaVenta = date('Y-m-d H:i:s'); // O la fecha que venga del externo: $externalData['created_at']
            $importeTotal = $externalData['total'];
            $subtotal = $externalData['subtotal'];
            $impuestoTotal = $externalData['tax'] ?? 0;

            // Estado: Si el externo dice "invoiced", lo marcamos facturado, si no, pendiente.
            $estatusLocal = ($externalData['status'] === 'invoiced') ? 'facturado' : 'pendiente';

            // B. INSERTAR EN TABLA TICKETS
            $sqlTicket = "INSERT INTO tickets (id_empresa, folio_ticket, fecha_venta, importe_t, subtotal, impuesto_t, estatus) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sqlTicket);
            $stmt->execute([
                $idEmpresa,
                $folio,
                $fechaVenta,
                $importeTotal,
                $subtotal,
                $impuestoTotal,
                $estatusLocal
            ]);

            $localTicketId = $this->conn->lastInsertId();

            // C. INSERTAR DETALLES (Productos)
            if (!empty($externalData['items'])) {
                $sqlDetalle = "INSERT INTO ticket_detalle (id_ticket, folio, id_prod_serv, descr, cant, precio_unit, importe, imp_1) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtDet = $this->conn->prepare($sqlDetalle);

                foreach ($externalData['items'] as $item) {
                    $stmtDet->execute([
                        $localTicketId,
                        $folio, // Folio del ticket padre
                        $item['product_code'] ?? '01010101', // Código SAT o genérico
                        $item['description'],
                        $item['quantity'],
                        $item['unit_price'],
                        $item['total_line'],
                        $item['tax_amount'] ?? 0
                    ]);
                }
            }

            // D. INSERTAR MÉTODO DE PAGO (Asumimos PUE / Efectivo por defecto si no viene)
            // Si el sistema externo te da estos datos, úsalos.
            $metodo = $externalData['payment_method'] ?? 'PUE';
            $forma = $externalData['payment_form'] ?? '01'; // 01 Efectivo

            $sqlPago = "INSERT INTO ticket_metodo_pago (id_ticket, metodo_pago, forma_pago, monto) VALUES (?, ?, ?, ?)";
            $stmtPay = $this->conn->prepare($sqlPago);
            $stmtPay->execute([$localTicketId, $metodo, $forma, $importeTotal]);

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Ticket importado correctamente',
                'local_id' => $localTicketId
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'message' => 'Error SQL: ' . $e->getMessage()];
        }
    }

    /**
     * 3. Notificar al sistema externo que ya se facturó
     */
    public function notifyExternalInvoiced($folioExterno, $uuid)
    {
        try {
            // POST https://api.sistema-externo.com/v1/tickets/12345/sync
            $this->httpClient->request('POST', "tickets/{$folioExterno}/sync", [
                'json' => [
                    'status' => 'invoiced',
                    'uuid' => $uuid,
                    'invoiced_at' => date('Y-m-d H:i:s')
                ]
            ]);
            return true;
        } catch (Exception $e) {
            // Si falla la notificación no detenemos el proceso, solo lo registramos en log
            error_log("No se pudo notificar al sistema externo: " . $e->getMessage());
            return false;
        }
    }
}
