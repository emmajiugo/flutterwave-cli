<?php
namespace App\Traits;

use Illuminate\Support\Facades\Http;

/**
 * Rave Base functions are included here
 */
trait FlutterwaveBase
{
    private $testPublicKey;
    private $testSecretKey;
    private $livePublicKey;
    private $liveSecretKey;
    private $baseUrl = "https://api.flutterwave.com/v3/";

    /**
     * Constructor
     */
    public function __construct()
    {
        // $this->testPublicKey = env('TEST_PUBLIC_KEY');
        $this->testSecretKey = env('TEST_SECRET_KEY');
        // $this->livePublicKey = env('LIVE_PUBLIC_KEY');
        $this->liveSecretKey = env('LIVE_SECRET_KEY');
    }

    /**
     * Processing
     */
    public function processing()
    {
        return "\nFlutterwave: ---> <comment>processing....</comment>\n";
    }

    /**
     * WEBHOOK PING
     */
    public function webhookPing($webhookUrl, $data, $hash) {
        try {

            $headers = array(
                'Content-Type: application/json',
                'verif-hash: '.$hash
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $webhookUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                return $this->curlError($ch);
            }

            curl_close ($ch);

            return $httpCode;

        } catch (\Throwable $th) {
            return $this->curlError($th->getMessage());
        }
    }

    /**
     * HTTP POST
     */
    public function HttpPost($endpoint, $data, $prod) {
        try {
            if ($prod) {
                // $pkey = $this->livePublicKey;
                $skey = $this->liveSecretKey;
            } else {
                // $pkey = $this->testPublicKey;
                $skey = $this->testSecretKey;
            }

            $url = $this->baseUrl . $endpoint;

            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$skey
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
            curl_setopt($ch, CURLOPT_TIMEOUT, 200);

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                return $this->curlError($ch);
            }

            curl_close ($ch);

            return json_decode($result, true);

        } catch (\Throwable $th) {
            return $this->curlError($th->getMessage());
        }
    }

    /**
     * HTTP GET
     */
    public function HttpGet($endpoint, $prod) {
        try {
            if ($prod) {
                // $pkey = $this->livePublicKey;
                $skey = $this->liveSecretKey;
            } else {
                // $pkey = $this->testPublicKey;
                $skey = $this->testSecretKey;
            }

            $url = $this->baseUrl . $endpoint;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


            $headers = array();
            $headers[] = "Authorization: Bearer ". $skey;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);

            if (curl_errno($ch)) {
                return $this->curlError($ch);
            }

            curl_close ($ch);

            return json_decode($result, true);

        } catch (\Throwable $th) {
            return $this->curlError($th->getMessage());
        }
    }

    /**
     * Error
     */
    private function curlError($ch = null) {
        return [
                "status" => "error",
                "message" => ($ch==null) ? "An error occured. Ensure you are connected to the internet." : curl_error($ch)
            ];
    }

    /**
     * Redirect to browser
     */
    private function redirect($url) {
        $this->line("");
        $this->comment("Opening in browser....");
        $this->line("redirecting: ---> <info>" . $url . "</info>");
        $this->line("");
        \Yuloh\Open\open($url);
    }

    /**
     * Copy a file, or recursively copy a folder and its contents
     * @param       string   $source    Source path
     * @param       string   $dest      Destination path
     * @param       int      $permissions New folder creation permissions
     * @return      bool     Returns true on success, false on failure
     */
    public function xcopy($source, $dest, $permissions = 0755)
    {
        // Check for symlinks
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        // Simple copy for a file
        if (is_file($source)) {
            return copy($source, $dest);
        }

        // Make destination directory
        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        // Loop through the folder
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Deep copy directories
            $this->xcopy("$source/$entry", "$dest/$entry", $permissions);
        }

        // Clean up
        $dir->close();
        return true;
    }

    public function docReference($key) {

        $doc = [
            "webhook" => "https://developer.flutterwave.com/docs/events",
            "test-cards" => "https://developer.flutterwave.com/docs/test-cards",
            "test-bank-accounts" => "https://developer.flutterwave.com/docs/test-bank-accounts",
            "encryption" => "https://developer.flutterwave.com/docs/encryption",
            "inline-modal" => "https://developer.flutterwave.com/docs/flutterwave-inline",
            "standard-modal" => "https://developer.flutterwave.com/docs/flutterwave-standard",
            "payment-links" => "https://developer.flutterwave.com/docs/payment-links",
            "payment-plans" => "https://developer.flutterwave.com/docs/payment-plans",
            "card-payment" => "https://developer.flutterwave.com/docs/card-payments",
            "account-payment" => "https://developer.flutterwave.com/docs/ng-account-payments",
            "bank-transfer" => "https://developer.flutterwave.com/docs/bank-transfer",
            "uk-account-payment" => "https://developer.flutterwave.com/docs/uk-account-payments",
            "mpesa" => "https://developer.flutterwave.com/docs/mpesa",
            "ussd-payment" => "https://developer.flutterwave.com/docs/ussd-payments",
            "rwanda-mobile-money" => "https://developer.flutterwave.com/docs/rwanda-mobile-money",
            "uganda-mobile-money" => "https://developer.flutterwave.com/docs/uganda-mobile-money",
            "zambia-mobile-money" => "https://developer.flutterwave.com/docs/zambia-mobile-money",
            "ghana-mobile-money" => "https://developer.flutterwave.com/docs/ghana-mobile-money",
            "transfers" => "https://developer.flutterwave.com/docs/transfers",
            "split-payments" => "https://developer.flutterwave.com/docs/split-payment",
            "bill-payments" => "https://developer.flutterwave.com/docs/bill-payments",
            "bvn-verification" => "https://developer.flutterwave.com/docs/bvn-verification-1",
            "account-verification" =>  "https://developer.flutterwave.com/docs/bank-account-verification",
            "transaction-verification" => "https://developer.flutterwave.com/docs/transaction-verification",
            "card-bin-verification" => "https://developer.flutterwave.com/docs/card-bin-verification"
        ];

        return $doc[$key];
    }

    /**
     * Card Webhook Response
     */
    public function cardWebhookResponse($response)
    {
        return [
            "id" => $response['id'],
            "txRef" => $response['tx_ref'],
            "flwRef" => $response['flw_ref'],
            "orderRef" => "URF_1594912363065_1125635",
            "paymentPlan" => null,
            "paymentPage" => null,
            "createdAt" => $response['created_at'],
            "amount" => $response['amount'],
            "charged_amount" => $response['charged_amount'],
            "status" => $response['status'],
            "IP" => $response['ip'],
            "currency" => $response['currency'],
            "appfee" => $response['app_fee'],
            "merchantfee" => $response['merchant_fee'],
            "merchantbearsfee" => 1,
            "customer" => [
                "id" => $response['customer']['id'],
                "phone" => $response['customer']['phone_number'],
                "fullName" => $response['customer']['name'],
                "customertoken" => null,
                "email" => $response['customer']['email'],
                "createdAt" => $response['customer']['created_at'],
                "updatedAt" => "2020-07-16T15:12:42.000Z",
                "deletedAt" => null,
                "AccountId" => $response['account_id']
            ],
            "entity" => [
                "card6" => "xxxxxx",
                "card_last4" => "xxxx"
            ],
            "event.type" => "CARD_TRANSACTION"
        ];
    }

    /**
     * Transfer Webhook Response
     */
    public function transferWebhookResponse($response)
    {
        return [
            "event.type" => "Transfer",
            "transfer" => [
                "id" => $response['id'],
                "account_number" => $response['account_number'],
                "bank_code" => $response['bank_code'],
                "fullname" => $response['full_name'],
                "date_created" => $response['created_at'],
                "currency" => $response['currency'],
                "debit_currency" => $response['debit_currency'],
                "amount" => $response['amount'],
                "fee" => $response['fee'],
                "status" => $response['status'],
                "reference" => $response['reference'],
                "meta" => $response['meta'],
                "narration" => $response['narration'],
                "approver" => $response['approver'],
                "complete_message" => $response['complete_message'],
                "requires_approval" => $response['requires_approval'],
                "is_approved" => $response['is_approved'],
                "bank_name" => $response['bank_name'],
                "proof" => null
            ]
        ];


    }
}
