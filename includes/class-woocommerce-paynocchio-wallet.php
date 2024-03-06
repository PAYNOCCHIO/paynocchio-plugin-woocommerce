<?php
/**
 * PaynocchioWallet class for managing digital wallets.
 *
 * @author Paynocchio inc.
 * @version 1.0
 * @link https://paynocchio.com/
 */
class Woocommerce_Paynocchio_Wallet {
    
    private $base_url;
    private $envId;
    private $secret;
    private $userId;
    private $signature;
    private $walletId;

    public function __construct($userId) {
        $this->base_url = get_option( 'woocommerce_paynocchio_settings')['base_url'];
        $this->secret = get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_SECRET_KEY];
        $this->env = get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_ENV_KEY];
        $this->userId = $userId;

        $this->signature = $this->createSignature();
    }

    public function getSignature()
    {
        return $this->signature;
    }

    private function sendRequest(string $method, string $url, string $body = ""): array {
        $headers = [
            'X-Wallet-Signature' => $this->signature,
        ];
        // print_r($this->signature); exit;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, true); 
        curl_setopt($ch, CURLOPT_URL, $this->base_url . $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true); 

        $streamVerboseHandle = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $streamVerboseHandle);

        $response = curl_exec($ch);
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status_code' => $httpCode,
            'response' => $response,
        ];
    }

    public function createSignature() {
        $signature = hash("sha256", $this->secret . "|" . $this->envId . "|" . $this->userId);
        // Add code to create a signature from the data
        return $signature;
    }

    public function getWalletById(string $walletId): array {
        $url = '/wallet/' . $walletId;
        
        $response = $this->sendRequest('GET', $url);

        return $response;
    }

    public function createWallet() {
        $data = [
            'env_id' => $this->envId,
            'user_id' => $this->userId,
            'currency_uuid' => get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_CURRENCY_KEY],
            'type_uuid' => get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_TYPE_KEY],
            'status_uuid' => get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_STATUS_KEY],
        ];

        $response = $this->sendRequest('POST', '/wallet/', json_encode($data));

        if($response['status_code'] === 201) {
            return true;
        } else {
            return $data;
        }
    }

    public function topUpWallet(string $walletId, float $amount): array {
        $data = [
            'env_id' => $this->envId,
            'user_id' => $this->userId,
            'wallet_id' => $walletId,
            'amount' => $amount,
        ];

        $response = $this->sendRequest('POST', '/wallet/topup', json_encode($data));

        return $response;
    }

    public function withdrawFromWallet(string $walletId, float $amount): array {
        $data = [
            'env_id' => $this->envId,
            'user_id' => $this->userId,
            'wallet_id' => $walletId,
            'amount' => $amount,
        ];

        $response = $this->sendRequest('POST', '/wallet/withdraw', json_encode($data));

        return $response;
    }

    public function makePayment(string $walletId, float $amount, string $orderId, float $bonusAmount = null): array {
        $data = [
            'env_id' => $this->envId,
            'user_id' => $this->userId,
            'wallet_id' => $walletId,
            'amount' => $amount,
            'order_id' => $orderId,
        ];

        if ($bonusAmount !== null) {
            $data['bonus_amount'] = $bonusAmount;
        }


        $response = $this->sendRequest('POST', '/wallet/payment', json_encode($data));

        return $response;
    }
    
    
    
    public function getOrdersList(string $orderId, array $filters = []): array {
        $url = '/orders/' . $orderId;

        $queryParams = [
            'env_id' => $this->envId,
            'user_id' => $this->userId,
            'wallet_id' => $this->walletId,
            // Add other filters from the $filters array
        ];

        // Example of using created_at filter
        if (isset($filters['created_at']['from'])) {
            $queryParams['created_at.from'] = $filters['created_at']['from'];
        }
        if (isset($filters['created_at']['to'])) {
            $queryParams['created_at.to'] = $filters['created_at']['to'];
        }

        $response = $this->sendRequest('GET', $url, $queryParams);

        return $response;
    }

    
    public function getOrderById(string $orderId): array {
        $url = '/orders/' . $orderId;

        $response = $this->sendRequest('GET', $url);

        return $response;
    }

}