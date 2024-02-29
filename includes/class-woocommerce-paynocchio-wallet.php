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
        $this->base_url = "https://wallet.stage.paynocchio.com"; 
        $this->secret = "47f6587d-a147-43b1-946b-de5dfb9bd6a5";
        $this->env = "17b9e735-2af8-4b38-821e-c2f67ef988ed";
        $this->userId = $userId;

        $this -> signature = $this -> createSignature();
    }


    
    private function sendRequest(string $method, string $url, string $body = ""): array {
        //41b075f43b4297b008b4b295a57e4e01c54884a23c985ffdea9158a460251466
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
        // print_r($response);
        // print_r(curl_getinfo($ch,CURLINFO_HEADER_OUT));
        // exit; 
        
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
            'env_id' => $this -> envId,
            'user_id' => $this -> userId,
            'currency_uuid' => "970d83de-1dce-47bd-a45b-bb92bf6df964",
            'type_uuid' => "93ac9017-4960-41bf-be6d-aa123884451d",
            'status_uuid' => "ae1b841f-2e56-4fb9-a935-2064304f8639",
        ];

        $response = $this->sendRequest('POST', '/wallet/', json_encode($data));

        if($response['status_code'] === 201) {
            return true;
        } else {
            return $response;
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