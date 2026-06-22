<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/firebase.php';

$firebase = new FirebaseHelper();
$action = $_GET['action'] ?? '';

// Helper to return error response
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $message]);
    exit;
}

// Helper to return success response
function sendSuccess($data = []) {
    echo json_encode(array_merge(['success' => true], $data));
    exit;
}

switch ($action) {
    case 'read':
        $rawTransactions = $firebase->read('transactions');
        $transactions = [];
        
        if (is_array($rawTransactions)) {
            // Firebase returns an associative array where key is ID. Let's format it.
            foreach ($rawTransactions as $id => $data) {
                if ($data === null) continue;
                $data['id'] = $id;
                $transactions[] = $data;
            }
        }
        
        // Sort transactions by date descending
        usort($transactions, function($a, $b) {
            return strcmp($b['date'] ?? '', $a['date'] ?? '');
        });
        
        sendSuccess(['data' => $transactions]);
        break;

    case 'create':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            sendError('Invalid input payload.');
        }

        // Validate data
        $name = trim($input['customer_name'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $address = trim($input['address'] ?? '');
        $service = trim($input['service_type'] ?? '');
        $weight = floatval($input['weight'] ?? 0);
        $totalCost = intval($input['total_cost'] ?? 0);
        $date = trim($input['date'] ?? date('Y-m-d'));
        $deliveryMethod = trim($input['delivery_method'] ?? 'Ambil Di Tempat');
        $status = trim($input['status'] ?? 'Proses');

        if (empty($name) || empty($phone) || empty($address) || empty($service) || $weight <= 0) {
            sendError('Semua field wajib diisi dan berat harus lebih dari 0.');
        }

        if (!array_key_exists($service, $services)) {
            sendError('Jenis layanan tidak valid.');
        }

        if (!in_array($deliveryMethod, ['Ambil Di Tempat', 'Diantar'])) {
            sendError('Jenis pengambilan tidak valid.');
        }

        $transactionData = [
            'customer_name' => $name,
            'phone' => $phone,
            'address' => $address,
            'service_type' => $service,
            'delivery_method' => $deliveryMethod,
            'weight' => $weight,
            'total_cost' => $totalCost,
            'date' => $date,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $result = $firebase->create('transactions', $transactionData);
        if ($result && isset($result['name'])) {
            sendSuccess(['id' => $result['name']]);
        } else {
            sendError('Gagal menyimpan transaksi ke database.');
        }
        break;

    case 'update':
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            sendError('ID transaksi diperlukan.');
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            sendError('Invalid input payload.');
        }

        // Validate data
        $name = trim($input['customer_name'] ?? '');
        $phone = trim($input['phone'] ?? '');
        $address = trim($input['address'] ?? '');
        $service = trim($input['service_type'] ?? '');
        $weight = floatval($input['weight'] ?? 0);
        $totalCost = intval($input['total_cost'] ?? 0);
        $date = trim($input['date'] ?? date('Y-m-d'));
        $deliveryMethod = trim($input['delivery_method'] ?? 'Ambil Di Tempat');
        $status = trim($input['status'] ?? 'Proses');

        if (empty($name) || empty($phone) || empty($address) || empty($service) || $weight <= 0) {
            sendError('Semua field wajib diisi dan berat harus lebih dari 0.');
        }

        if (!array_key_exists($service, $services)) {
            sendError('Jenis layanan tidak valid.');
        }

        if (!in_array($deliveryMethod, ['Ambil Di Tempat', 'Diantar'])) {
            sendError('Jenis pengambilan tidak valid.');
        }

        $transactionData = [
            'customer_name' => $name,
            'phone' => $phone,
            'address' => $address,
            'service_type' => $service,
            'delivery_method' => $deliveryMethod,
            'weight' => $weight,
            'total_cost' => $totalCost,
            'date' => $date,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $firebase->update('transactions', $id, $transactionData);
        if ($result) {
            sendSuccess();
        } else {
            sendError('Gagal memperbarui transaksi.');
        }
        break;

    case 'update_status':
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            sendError('ID transaksi diperlukan.');
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $status = trim($input['status'] ?? '');

        if (!in_array($status, ['Proses', 'Selesai', 'Sudah Diambil', 'Sudah Diantar'])) {
            sendError('Status tidak valid.');
        }

        $result = $firebase->update('transactions', $id, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            sendSuccess();
        } else {
            sendError('Gagal memperbarui status transaksi.');
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            sendError('ID transaksi diperlukan.');
        }

        $result = $firebase->delete('transactions', $id);
        if ($result !== false) {
            sendSuccess();
        } else {
            sendError('Gagal menghapus transaksi.');
        }
        break;

    default:
        sendError('Action not found.', 404);
        break;
}
