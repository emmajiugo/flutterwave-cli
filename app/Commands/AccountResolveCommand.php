<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class AccountResolveCommand extends Command
{
    //Add Trait
    use FlutterwaveBase {
        FlutterwaveBase::__construct as FlutterwaveConstruct;
    }

    /**
     * Call parent constructor
     */
    public function __construct()
    {
        parent::__construct();
        self::FlutterwaveConstruct();
    }

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'account:resolve {account-number : the bank account number/Flutterwave Merchant ID} {bank-code : the bank code of the required bank. (get all banks by country using the <command> bank)} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Resolve account number to get the full details';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $accountNumber = $this->argument('account-number');
        $bankCode = $this->argument('bank-code');
        $prod = $this->option('live');

        $this->sendPayload($accountNumber, $bankCode, $prod);
    }

    /**
     * Payload
     */
    public function sendPayload($accountNumber, $bankCode, $prod) {

        // processing...
        $this->line($this->processing());

        $endpoint = "accounts/resolve";

        $payload = [
            "account_number" => $accountNumber,
            "account_bank" => $bankCode
        ];

        $res = $this->HttpPost($endpoint, $payload, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        // format output
        $headers = ['Response', 'Value'];

        $result = array(
            [
                "Account Number",
                $res['data']['account_number']
            ],
            [
                "Account Name",
                $res['data']['account_name']
            ]
        );

        $this->table($headers, $result);
        $this->info("");

    }
}
