<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class BalanceCommand extends Command
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
    protected $signature = 'balance {--currency= : currency in ISO (e.g. NGN, GHS, USD, EUR etc)} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get Flutterwave Account Balance';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currency = $this->option('currency');
        $prod = $this->option('live');

        // dd($this->sendPayload($currency, $prod));
        $this->sendPayload($currency, $prod);
    }

    /**
     * Docs
     */
    public function sendPayload($currency, $prod) {

        // processing...
        $this->line($this->processing());

        $endpoint = "balances";

        if ($currency !== null) $endpoint = "balances/" . $currency;

        $res = $this->HttpGet($endpoint, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        // format output
        $headers = ['Currency', 'Available Balance', 'Ledger Balance'];

        $result = [];

        if (array_key_exists("currency" , $res['data'])) {
            array_push($result, array(
                $res['data']['currency'],
                $res['data']['available_balance'],
                $res['data']['ledger_balance']
            ));
        } else {
            foreach ($res['data'] as $value) {
                array_push($result, array(
                    $value['currency'],
                    $value['available_balance'],
                    $value['ledger_balance']
                ));
            }
        }

        $this->table($headers, $result);
        $this->info("");

    }
}
