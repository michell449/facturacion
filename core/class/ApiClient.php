<?php
/**
 * ApiClient: Integración local para Tickets/Facturación
 *
 * Esta clase encapsula operaciones relacionadas con tickets usando la base
 * de datos local (sin llamadas a servicios externos).
 */

require_once __DIR__ . '/db.php';

class ApiClient
{
    /** @var PDO */
    private $conn;

    /**
     * @param PDO|null $connection Conexión existente; si es null se crea una nueva.
     */
    public function __construct(?PDO $connection = null)
    {
        if ($connection instanceof PDO) {
            $this->conn = $connection;
        } else {
            $db = new Database();
            $this->conn = $db->getConnection();
        }
    }

    /**
     * Obtiene información completa del ticket, sus partidas y método de pago.
     *
     * @param int $ticketId ID del ticket.
     * @return array [success, data|message]
     */
    public function getTicket(int $ticketId): array
    {
        // Header del ticket + sucursal
        $sql = "SELECT t.*, e.nombre AS nombre_sucursal, e.razon_social, e.rfc
                FROM tickets t
                JOIN empresas e ON e.id_empresa = t.id_empresa
                WHERE t.id_ticket = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$ticketId]);
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            return ['success' => false, 'message' => 'Ticket no encontrado'];
        }

        // Detalle del ticket (productos)
        $stmtDet = $this->conn->prepare("SELECT * FROM ticket_detalle WHERE id_ticket = ? ORDER BY id_detalle ASC");
        $stmtDet->execute([$ticketId]);
        $items = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

        // Método de pago (pueden existir varios; tomamos todos)
        $stmtPay = $this->conn->prepare("SELECT metodo_pago, forma_pago, monto FROM ticket_metodo_pago WHERE id_ticket = ?");
        $stmtPay->execute([$ticketId]);
        $pagos = $stmtPay->fetchAll(PDO::FETCH_ASSOC);

        // Estado local de facturación
        $estadoLocal = $ticket['estatus'] ?? 'pendiente';

        $stmtFac = $this->conn->prepare("SELECT uuid, estatus, folio_interno, serie_interno, id_factura FROM facturas WHERE id_ticket = ? ORDER BY id_factura DESC LIMIT 1");
        $stmtFac->execute([$ticketId]);
        $fact = $stmtFac->fetch(PDO::FETCH_ASSOC);
        if ($fact) {
            // Si hay factura timbrada, forzamos facturado
            if ($fact['estatus'] === 'timbrada') {
                $estadoLocal = 'facturado';
            }
        }

        return [
            'success' => true,
            'data' => [
                'ticket' => [
                    'id_ticket' => (int)$ticket['id_ticket'],
                    'id_empresa' => (int)$ticket['id_empresa'],
                    'folio_ticket' => $ticket['folio_ticket'],
                    'fecha_venta' => $ticket['fecha_venta'],
                    'importe_total' => (float)$ticket['importe_t'],
                    'subtotal' => (float)$ticket['subtotal'],
                    'impuesto_total' => (float)$ticket['impuesto_t'],
                    'sucursal' => $ticket['nombre_sucursal'] ?? $ticket['razon_social'],
                    'razon_social' => $ticket['razon_social'],
                    'rfc_empresa' => $ticket['rfc'],
                    'estatus' => $estadoLocal,
                ],
                'productos' => $items,
                'pagos' => $pagos,
                'factura' => $fact ?: null,
                'puede_facturar' => ($estadoLocal !== 'facturado'),
                'puede_cancelar' => ($estadoLocal === 'facturado' && $fact && $fact['estatus'] === 'timbrada'),
            ]
        ];
    }

    /**
     * Busca tickets con filtros opcionales.
     *
     * @param array $filtros [id_empresa?, folio_ticket?, estatus?, fecha_desde?, fecha_hasta?, limite?]
     * @return array [success, data[], message?]
     */
    public function searchTickets(array $filtros = []): array
    {
        $where = [];
        $params = [];

        if (!empty($filtros['id_empresa'])) {
            $where[] = "t.id_empresa = ?";
            $params[] = (int)$filtros['id_empresa'];
        }

        if (!empty($filtros['folio_ticket'])) {
            $where[] = "t.folio_ticket LIKE ?";
            $params[] = '%' . $filtros['folio_ticket'] . '%';
        }

        if (!empty($filtros['estatus'])) {
            $where[] = "t.estatus = ?";
            $params[] = $filtros['estatus'];
        }

        if (!empty($filtros['fecha_desde'])) {
            $where[] = "t.fecha_venta >= ?";
            $params[] = $filtros['fecha_desde'];
        }

        if (!empty($filtros['fecha_hasta'])) {
            $where[] = "t.fecha_venta <= ?";
            $params[] = $filtros['fecha_hasta'];
        }

        $whereClause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';
        $limite = isset($filtros['limite']) ? (int)$filtros['limite'] : 50;

        $sql = "SELECT t.*, e.nombre AS nombre_sucursal, e.razon_social
                FROM tickets t
                JOIN empresas e ON e.id_empresa = t.id_empresa
                {$whereClause}
                ORDER BY t.fecha_venta DESC, t.id_ticket DESC
                LIMIT {$limite}";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Para cada ticket, obtener conteo de productos y método de pago
            $resultados = [];
            foreach ($tickets as $ticket) {
                $idTicket = (int)$ticket['id_ticket'];

                // Contar productos
                $stmtCount = $this->conn->prepare("SELECT COUNT(*) as total FROM ticket_detalle WHERE id_ticket = ?");
                $stmtCount->execute([$idTicket]);
                $count = $stmtCount->fetch(PDO::FETCH_ASSOC);

                // Obtener métodos de pago
                $stmtPago = $this->conn->prepare("SELECT metodo_pago, forma_pago, monto FROM ticket_metodo_pago WHERE id_ticket = ?");
                $stmtPago->execute([$idTicket]);
                $pagos = $stmtPago->fetchAll(PDO::FETCH_ASSOC);

                // Verificar si tiene factura
                $stmtFac = $this->conn->prepare("SELECT uuid, estatus FROM facturas WHERE id_ticket = ? ORDER BY id_factura DESC LIMIT 1");
                $stmtFac->execute([$idTicket]);
                $factura = $stmtFac->fetch(PDO::FETCH_ASSOC);

                $estadoLocal = $ticket['estatus'] ?? 'pendiente';
                if ($factura && $factura['estatus'] === 'timbrada') {
                    $estadoLocal = 'facturado';
                }

                $resultados[] = [
                    'id_ticket' => $idTicket,
                    'folio_ticket' => $ticket['folio_ticket'],
                    'fecha_venta' => $ticket['fecha_venta'],
                    'sucursal' => $ticket['nombre_sucursal'] ?? $ticket['razon_social'],
                    'id_empresa' => (int)$ticket['id_empresa'],
                    'importe_total' => (float)$ticket['importe_t'],
                    'subtotal' => (float)$ticket['subtotal'],
                    'impuesto_total' => (float)$ticket['impuesto_t'],
                    'total_productos' => (int)$count['total'],
                    'metodos_pago' => $pagos,
                    'estatus' => $estadoLocal,
                    'uuid_factura' => $factura['uuid'] ?? null,
                    'puede_facturar' => ($estadoLocal !== 'facturado'),
                    'puede_cancelar' => ($estadoLocal === 'facturado'),
                ];
            }

            return [
                'success' => true,
                'data' => $resultados,
                'total' => count($resultados)
            ];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()];
        }
    }

    /**
     * Marca un ticket como facturado en el sistema local.
     * No timbra ni crea CFDI; solo actualiza el estado del ticket.
     *
     * @param int $ticketId
     * @param string|null $uuidFactura UUID para referencia opcional
     * @return array {success, message}
     */
    public function markAsInvoiced(int $ticketId, ?string $uuidFactura = null): array
    {
        try {
            // Validar ticket
            $chk = $this->conn->prepare("SELECT id_ticket, estatus FROM tickets WHERE id_ticket = ?");
            $chk->execute([$ticketId]);
            $ticket = $chk->fetch(PDO::FETCH_ASSOC);
            
            if (!$ticket) {
                return ['success' => false, 'message' => 'Ticket no existe'];
            }

            if ($ticket['estatus'] === 'facturado') {
                return ['success' => false, 'message' => 'El ticket ya está facturado'];
            }

            $upd = $this->conn->prepare("UPDATE tickets SET estatus = 'facturado' WHERE id_ticket = ?");
            $upd->execute([$ticketId]);

            return ['success' => true, 'message' => 'Ticket marcado como facturado'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }

    /**
     * Revierte un ticket a pendiente (p.ej. tras cancelar CFDI).
     *
     * @param int $ticketId
     * @return array {success, message}
     */
    public function markAsUninvoiced(int $ticketId): array
    {
        try {
            $chk = $this->conn->prepare("SELECT id_ticket FROM tickets WHERE id_ticket = ?");
            $chk->execute([$ticketId]);
            if (!$chk->fetch()) {
                return ['success' => false, 'message' => 'Ticket no existe'];
            }

            $upd = $this->conn->prepare("UPDATE tickets SET estatus = 'pendiente' WHERE id_ticket = ?");
            $upd->execute([$ticketId]);
            
            return ['success' => true, 'message' => 'Ticket marcado como pendiente'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()];
        }
    }
}
?>