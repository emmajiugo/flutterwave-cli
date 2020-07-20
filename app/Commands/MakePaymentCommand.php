<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class MakePaymentCommand extends Command
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
    protected $signature = 'payment {email : user email} {amount : amount to be paid} {--currency= : currency in IS0 (e.g. NGN, GHS, USD, EUR etc.} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Make quick payment using the Flutterwave checkout';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        $amount = $this->argument('amount');
        $currency = $this->option('currency');
        $prod = $this->option('live');

        $this->sendPayload($email, $amount, $currency, $prod);
    }

    /**
     * Docs
     */
    public function sendPayload($email, $amount, $currency, $prod) {

        // processing...
        $this->line($this->processing());

        $endpoint = "payments";

        if ($currency === null) $currency = "NGN";

        $reference = "FLW-CLI-" . (rand(10,100) . time());

        // format output
        $headers = ['Request', 'Value'];

        $result = array(
            [
                "Email",
                $email
            ],
            [
                "Currency",
                $currency
            ],
            [
                "Amount",
                $amount
            ],
            [
                "Reference",
                $reference
            ],
        );

        $this->table($headers, $result);
        $this->info("");

        // payload
        $data = [
            "tx_ref" => $reference,
            "amount" => $amount,
            "currency" => $currency,
            "redirect_url" => "https://developer.flutterwave.com/docs/getting-started",
            "customer" => [
                "email" => $email
            ]
        ];

        $res = $this->HttpPost($endpoint, $data, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        // redirect to browser
        $this->redirect($res['data']['link']);

    }
}
