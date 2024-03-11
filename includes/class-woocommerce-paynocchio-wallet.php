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
        $this->envId = get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_ENV_KEY];
        $this->userId = $userId;

        $this->signature = $this->createSignature();
    }

    private function sendRequest(string $method, string $url, string $body = ""): array {
        $headers = [
            'X-Wallet-Signature:'. $this->signature,
            'X-API-KEY: X-API-KEY',
            'Content-Type: application/json'
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);


        curl_close($curl);

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
        $url = '/wallet/' . $walletId . '?environment_uuid=' . $this->envId;

        $response = $this->sendRequest('GET', $url);

        return $response;
    }

    public function createWallet() {
        $data = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            PAYNOCCHIO_USER_UUID_KEY => $this->userId,
            PAYNOCCHIO_CURRENCY_KEY => get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_CURRENCY_KEY],
            PAYNOCCHIO_TYPE_KEY => get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_TYPE_KEY],
            PAYNOCCHIO_STATUS_KEY => get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_STATUS_KEY],
        ];

        $response = $this->sendRequest('POST', '/wallet/', json_encode($data, JSON_UNESCAPED_SLASHES));

        if($response['status_code'] === 201) {
            $json = json_decode($response['response']);
            return json_encode(['status'=> 'success', 'wallet' => $json->uuid,]);
        } else {
            return json_encode($response);
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

    public function getWalletBalance(string $walletId): array
    {
        $user_paynocchio_wallet = $this->getWalletById($walletId);
        if($user_paynocchio_wallet['status_code'] === 200) {
            $json_response = json_decode($user_paynocchio_wallet['response']);
            return [
                'balance' => $json_response->balance->current,
                'bonuses' => $json_response->rewarding_balance,
                'number' => $json_response->number,
            ];
        }

        return [];
    }

}