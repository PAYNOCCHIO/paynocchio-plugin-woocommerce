<?php

use core\uuid;

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
    private $simpleSignature;
    private $walletId;

    public function __construct($userId) {
        $this->base_url = get_option( 'woocommerce_paynocchio_settings')['base_url'];
        $this->secret = get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_SECRET_KEY];
        $this->envId = get_option( 'woocommerce_paynocchio_settings')[PAYNOCCHIO_ENV_KEY];
        $this->userId = $userId;

        $this->signature = $this->createSignature();
        $this->simpleSignature = $this->createSimpleSignature();
    }

    private function sendRequest(string $method, string $url, string $body = "", bool $simple = false): array {
        $headers = [
            //'X-API-KEY: X-API-KEY',
            'Content-Type: application/json'
        ];

        $WPPPG = new Woocommerce_Paynocchio_Payment_Gateway;
        $test_mode = $WPPPG->get_test_mode() ? 'on' : 'off';

        $headers[] = 'X-TEST-MODE-SWITCH:'. $test_mode;

        if($simple) {
            $headers[] = 'X-Company-Signature:'. $this->simpleSignature;
        } else {
            $headers[] = 'X-Wallet-Signature:'. $this->signature;
        }

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

    public function get_userId()
    {
        return $this->userId;
    }

    public function get_secret()
    {
        return $this->secret;
    }

    public function get_env()
    {
        return $this->envId;
    }

    public function wallet_uuid()
    {
        return get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY, true) ? get_user_meta(get_current_user_id(), PAYNOCCHIO_WALLET_KEY, true) : wp_generate_uuid4();
    }

    /**
     *  X-Wallet-Signature
     */
    public function createSignature()
    {
        return hash("sha256", $this->secret . "|" . $this->envId . "|" . $this->userId);
    }
    /**
    *  X-Company-Signature
    */
    public function createSimpleSignature() {
        $signature = hash("sha256", $this->secret . "|" . $this->envId);
        return $signature;
    }

    /** Get Signature
     * @param false $simple
     * @return bool|string
     */
    public function getSignature($simple = false): bool|string
    {
        return $simple ? $this->simpleSignature : $this->signature;
    }

    /**
     *  Get Wallet by ID
     */
    public function getWalletById(string $walletId): array {
        $data = [
            PAYNOCCHIO_SECRET_KEY => $this->secret,
        ];

        $url = '/wallet/' . $walletId . '?environment_uuid=' . $this->envId . '&user_uuid='. $this->userId;

        return $this->sendRequest('GET', $url, json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    /**
     *  Create Wallet
     */
    public function healthCheck() {
        $data = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            PAYNOCCHIO_SECRET_KEY => $this->secret,
        ];

        $response = $this->sendRequest('POST', '/healthcheck/', json_encode($data, JSON_UNESCAPED_SLASHES));

        if($response['status_code'] === 200) {
            $json = json_decode($response['response']);
            return json_encode(['status'=> 'success', 'message' => $json->message,]);
        } else {
            return json_encode(['status'=> 'error', 'message' => $response['status_code'],]);
        }
    }

    /**
     *  Create Wallet
     */
    public function createWallet() {
        $data = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            PAYNOCCHIO_USER_UUID_KEY => $this->userId,
            PAYNOCCHIO_CURRENCY_KEY => '970d83de-1dce-47bd-a45b-bb92bf6df964',
            PAYNOCCHIO_TYPE_KEY => '93ac9017-4960-41bf-be6d-aa123884451d',
            PAYNOCCHIO_STATUS_KEY => 'ef8da49e-a9e3-4726-8c26-f8d2bfd6a093',
        ];

        $response = $this->sendRequest('POST', '/wallet/', json_encode($data, JSON_UNESCAPED_SLASHES));

        if($response['status_code'] === 201) {
            $json = json_decode($response['response']);
            $this->walletId = $json->uuid;
            return json_encode(['status'=> 'success', 'wallet' => $json->uuid,]);
        } else {
            return json_encode($response);
        }
    }

    /**
     *  TopUp Wallet
     */
    public function topUpWallet(string $walletId, float $amount, string $redirect_url): array {
        $data = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            PAYNOCCHIO_USER_UUID_KEY => $this->userId,
            PAYNOCCHIO_WALLET_KEY => $walletId,
            "currency" => "USD",
            'amount' => $amount,
            'redirect_url' => $redirect_url,
        ];

        $response = $this->sendRequest('POST', '/operation/topup', json_encode($data));

        return $response;
    }

    /**
     *  Withdraw Wallet
     */
    public function withdrawFromWallet(string $walletId, float $amount): array {
        $data = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            PAYNOCCHIO_USER_UUID_KEY => $this->userId,
            PAYNOCCHIO_WALLET_KEY => $walletId,
            "currency" => "USD",
            'amount' => $amount,
            'status_type' => 'ae1b841f-2e56-4fb9-a935-2064304f8639', // TODO Check if it's needed
        ];

        $response = $this->sendRequest('POST', '/operation/withdraw', json_encode($data));

        return $response;
    }

    /**
     *  Make Payment
     */
    public function makePayment(string $walletId, $fullAmount, $amount, string $orderId, $bonusAmount = null): array
    {
        $data = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            PAYNOCCHIO_USER_UUID_KEY => $this->userId,
            PAYNOCCHIO_WALLET_KEY => $walletId,
            "currency" => "USD",
            "full_amount" => $fullAmount,
            "amount" => $amount,
            "external_order_id" => $orderId,
        ];

        if ($bonusAmount !== null) {
            $data['bonus_amount'] = $bonusAmount;
        }
        $response = $this->sendRequest('POST', '/operation/payment', json_encode($data));

        return $response;
    }

    /**
     *  Get Orders List
     */
    public function getOrdersList(string $orderId, array $filters = []): array {
        $url = '/orders/' . $orderId;

        $queryParams = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            PAYNOCCHIO_USER_UUID_KEY => $this->userId,
            PAYNOCCHIO_WALLET_KEY => $this->walletId,
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

    /**
     *  ChargeBack
     */
    public function chargeBack($orderId, $walletId, $amount) {
        $data = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            PAYNOCCHIO_USER_UUID_KEY => $this->userId,
            PAYNOCCHIO_WALLET_KEY => $walletId,
            "currency" => "USD",
            'amount' => $amount,
            'external_order_id' => $orderId,
        ];

        return $this->sendRequest('POST', '/operation/chargeback', json_encode($data));
    }

    /**
     *  Get Order by ID
     */
    public function getOrderById(string $orderId): array {
        $url = '/orders/' . $orderId .'/?environment_uuid='.$this->envId;

        return $this->sendRequest('GET', $url);
    }

    /**
     *  Wallet information
     */

    public function getWalletBalance(string $walletId): array
    {
        $user_paynocchio_wallet = $this->getWalletById($walletId);
        if($user_paynocchio_wallet['status_code'] === 200) {
            $json_response = json_decode($user_paynocchio_wallet['response']);
            return [
                'balance' => $json_response->balance->current,
                'bonuses' => intval($json_response->rewarding_balance),
                'number' => $json_response->number,
                'status' => $json_response->status->code,
                'code' => $json_response->status->code,
            ];
        }

        return [
            'balance' => 0,
            'bonuses' => 0,
            'number' => 0,
            'status' => $user_paynocchio_wallet['response'],
            'code' => $user_paynocchio_wallet['status_code'],
        ];
    }

    public function getWalletStatuses() {
        $url = '/status/';

        $response = $this->sendRequest('GET', $url);
        $json_response = json_decode($response['response']);

        return [
            'ACTIVE' => array_reduce($json_response->statuses, static function ($carry, $item) {
                return $carry === false && $item->code === 'ACTIVE' ? $item->uuid : $carry;
            }, false),
            'SUSPEND' => array_reduce($json_response->statuses, static function ($carry, $item) {
                return $carry === false && $item->code === 'SUSPEND' ? $item->uuid : $carry;
            }, false),
            'BLOCKED' => array_reduce($json_response->statuses, static function ($carry, $item) {
                return $carry === false && $item->code === 'BLOCKED' ? $item->uuid : $carry;
            }, false),
        ];
    }

    /**
    *  Update Wallet Status
    */
    public function updateWalletStatus(string $wallet_id, string $status)
    {
        $data = [
            PAYNOCCHIO_ENV_KEY => $this->envId,
            'uuid' => $wallet_id,
            PAYNOCCHIO_STATUS_KEY => $status,
        ];

        return $this->sendRequest('PATCH', '/wallet/', json_encode($data), true);
    }

    /**
     * Get Wallet Structure
     * This needed to check conversion rates
     */

    public function getEnvironmentStructure(): array
    {
        $url = '/wallet/environment-structure/?user_uuid=' . $this->userId . '&environment_uuid=' . $this->envId;

        $response = $this->sendRequest('GET', $url);
        $json_response = json_decode($response['response']);
        if($response['status_code'] === 200) {
            $filtered_rewards = self::filterEnvRewardingGroups($json_response->rewarding_groups);
            return [
                'card_balance_limit' => $json_response->card_balance_limit,
                'daily_transaction_limit' => $json_response->daily_transaction_limit,
                'multiple_accounts_limit' => $json_response->multiple_accounts_limit,
                'minimum_topup_amount' => $json_response->minimum_topup_amount,
                'bonus_conversion_rate' => $json_response->bonus_conversion_rate,
                'allow_withdraw' => $json_response->allow_withdraw,
                'rewarding_group' => end($filtered_rewards),
            ];
        }
    }

    /**
     * Calculate Commission and bonuses for operations
     */
    public function getStructureCalculation($amount, $operation_type, $wallet_balance_check): array
    {
        $operation_query = $operation_type ? '&operation_type=' . $operation_type : '';
        $wallet_balance_check_query = $wallet_balance_check == 'false' ? '&wallet_balance_check=false' : '';
        $url = '/wallet/structure_calculation?environment_uuid='.$this->envId.'&user_uuid='.$this->userId.'&wallet_uuid='.$this->wallet_uuid().'&amount='.(float) $amount . $operation_query . $wallet_balance_check_query;

        $response = $this->sendRequest('GET', $url);
        $json_response = json_decode($response['response']);
        if($response['status_code'] === 200) {
            return [
                'conversion_rate' => $json_response->conversion_rate,
                'operations_data' => $json_response->operations_data,
            ];
        } elseif ($response['detail'] == 'Not enough balance') {
            return [
                'error' => 'Not enough balance',
                'status' => '400',
            ];
        } else {
            return [
                'error' => 'API Error',
                'status' => '500',
                'url' => $url,
                'query' => $wallet_balance_check_query,
                'check' => $wallet_balance_check
            ];
        }
    }

    /**
     * Transform and merge identical rules
     * @param $data
     * @return array|null
     */
    public function transformRewardingRules($data)
    {
        $result = [];

        if ($data) {
            foreach ($data as $item) {
                $existing = null;

                foreach ($result as &$el) {
                    if ($el->operation_type === $item->operation_type &&
                        $el->min_amount === $item->min_amount &&
                        $el->max_amount === $item->max_amount) {
                        $existing = &$el;
                        break;
                    }
                }

                if ($existing) {
                    $existing['value'] += $item->value;
                } else {
                    $result[] = $item;
                }
            }

            return $result;
        }

        return null;
    }

    /**
     * Calculate Rewarding rule for static data
     * @param $num
     * @param $operationType
     * @return array
     */
    public function getCurrentRewardRule($num, $operationType) {
        $obj = $this->transformRewardingRules($this->getEnvironmentStructure()['rewarding_group']->rewarding_rules);
        $totalValue = 0;
        $minAmount = INF;
        $maxAmount = -INF;
        $value_type = null;
        $conversion_rate = 1;

        if ($obj) {
            foreach ($obj as $item) {
                $conversion_rate = $item->conversion_rate;
                $value_type = $item->value_type;
                if ($item->operation_type === $operationType && $num >= $item->min_amount && $num <= $item->max_amount) {
                    $totalValue += $item->value;
                    if ($item->min_amount < $minAmount) {
                        $minAmount = $item->min_amount;
                    }
                    if ($item->max_amount > $maxAmount) {
                        $maxAmount = $item->max_amount;
                    }
                }
            }
        }
        return [
            'totalValue' => $value_type === 'percentage' ? $totalValue / $conversion_rate / 100 : $totalValue,
            'minAmount' => $minAmount,
            'maxAmount' => $maxAmount,
            'value_type' => $value_type,
            'conversion_rate' => $conversion_rate,
            'operationType' => $operationType,
        ];
    }

    /**
     * Filter the rewarding groups array to return only active and not expired campaigns
     * @param $groups
     * @return array
     */

    static function filterEnvRewardingGroups($groups): array
    {
        return array_values(array_filter($groups, [__CLASS__, 'checkFilter']));
    }

    /**
     * Filter rule for campaign filtering
     * @param $group
     * @return bool
     */
    static function checkFilter($group): bool
    {
        return $group->active && strtotime($group->date_from) <= time() && strtotime($group->date_to) >= time();
    }

    /**
     * Get Transaction History
     * This needed to check wallet Limit
     */
}