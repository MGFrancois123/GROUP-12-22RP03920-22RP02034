<?php
class SMS {
    // Sandbox credentials for student/testing use
    private $apiKey = "atsk_c86cfe87c595dead54bcfb56a97b5f6abc01ca98aa60be024225f71590380105846ec458"; // Your sandbox API key
    private $senderId = "ORDERINGSYS"; // Default senderId for sandbox
    private $baseUrl = "https://api.africastalking.com/version1/messaging";
    private $username = "sandbox";

    public function sendSMS($phoneNumber, $message) {
        try {
            if (empty($phoneNumber) || empty($message)) {
                throw new Exception("Phone number and message are required");
            }

            // Format phone number to +2547XXXXXXXX for Kenya (sandbox only)
            $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
            if (strpos($phoneNumber, '0') === 0) {
                $phoneNumber = '250' . substr($phoneNumber, 1);
            }
            if (strpos($phoneNumber, '254') !== 0) {
                $phoneNumber = '250' . $phoneNumber;
            }
            $phoneNumber = '+' . $phoneNumber;

            if (!preg_match('/^\+2507[0-9]{8}$/', $phoneNumber)) {
                throw new Exception("Invalid Kenyan phone number format: " . $phoneNumber);
            }

            $headers = [
                'Accept: application/json',
                'Content-Type: application/x-www-form-urlencoded',
                'apiKey: ' . $this->apiKey
            ];

            $data = [
                'username' => $this->username,
                'to' => $phoneNumber,
                'message' => $message,
                'from' => $this->senderId
            ];

            error_log('AT SMS Request: ' . json_encode($data));

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->baseUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                throw new Exception("Curl error: " . curl_error($ch));
            }

            curl_close($ch);

            error_log('AT SMS Response: ' . $response);

            if ($httpCode != 201) {
                throw new Exception("SMS API returned error code: " . $httpCode . ", response: " . $response);
            }

            $responseData = json_decode($response, true);
            if (!$responseData || !isset($responseData['SMSMessageData'])) {
                throw new Exception("Invalid response from SMS API: " . $response);
            }

            return [
                'success' => true,
                'response' => $responseData
            ];

        } catch (Exception $e) {
            error_log("SMS Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function sendOrderConfirmation($userName, $orderDetails, $totalAmount, $balance, $address) {
        try {
            $message = "[AFRICAN TALK SMS]\n\n";
            $message .= "Hello " . $userName . ",\n";
            $message .= "Your order for:\n";
            $message .= $orderDetails . "\n";
            $message .= "Total Paid: " . number_format($totalAmount, 2) . "\n";
            $message .= "Balance Remaining: " . number_format($balance, 2) . "\n\n";
            $message .= "We appreciate your trust in us.\n";
            $message .= "Delivery will be made to: " . $address . "\n\n";
            $message .= "Thank you!";

            return $message;
        } catch (Exception $e) {
            error_log("Error formatting order confirmation: " . $e->getMessage());
            return false;
        }
    }
}
?> 