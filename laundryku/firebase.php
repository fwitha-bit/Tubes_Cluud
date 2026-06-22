<?php
require_once __DIR__ . '/config.php';

/**
 * Lightweight helper to communicate with Firebase Realtime Database using REST API
 */
class FirebaseHelper {
    private $dbUrl;

    public function __construct() {
        $this->dbUrl = rtrim(FIREBASE_DB_URL, '/') . '/';
    }

    /**
     * Perform HTTP request to Firebase REST endpoint
     */
    private function request($path, $method = 'GET', $data = null) {
        $url = $this->dbUrl . $path;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass certificate verification for dev compatibility

        if ($data !== null) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($jsonData)
            ]);
        }

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            error_log("Firebase API Connection Error: " . $error);
            return false;
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }

        error_log("Firebase API HTTP Error Code: " . $httpCode . ", Response: " . $response);
        return false;
    }

    /**
     * Create a record (Sends POST and returns Firebase generated unique key)
     */
    public function create($path, $data) {
        return $this->request($path . '.json', 'POST', $data);
    }

    /**
     * Read all records or single record
     */
    public function read($path, $id = null) {
        $targetPath = $id ? $path . '/' . $id : $path;
        return $this->request($targetPath . '.json', 'GET');
    }

    /**
     * Update a record partially (Sends PATCH)
     */
    public function update($path, $id, $data) {
        return $this->request($path . '/' . $id . '.json', 'PATCH', $data);
    }

    /**
     * Delete a record (Sends DELETE)
     */
    public function delete($path, $id) {
        return $this->request($path . '/' . $id . '.json', 'DELETE');
    }
}
