<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class FxRatesCommand extends Command
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
    protected $signature = 'fx:rate {--amount= : amount for conversion} {--from= : currency to convert from (e.g. NGN, GHS, USD, EUR etc)} {--to= : currency to convert to (e.g. NGN, GHS, USD, EUR etc} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get Flutterwave FX Rate';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $amount = $this->option('amount');
        $from = $this->option('from');
        $to = $this->option('to');
        $prod = $this->option('live');

        $this->sendPayload($amount, $from, $to, $prod);
    }

    /**
     * Docs
     */
    public function sendPayload($amount, $from, $to, $prod) {

        if ($amount === null) {
            $this->error("Amount is requred. (e.g: --amount=100 tag)");
            exit();
        }
        if ($from === null) {
            $this->error("From is requred. (e.g: --from=USD tag)");
            exit();
        }
        if ($to === null) {
            $this->error("To is requred. (e.g: --to=NGN tag)");
            exit();
        }

        // processing...
        $this->line($this->processing());

        $endpoint = "rates?amount=" . $amount . "&from=" . $from . "&to=" . $to;
        $res = $this->HttpGet($endpoint, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        // format output
        $headers = ['Rate', 'From', 'To'];

        $result = array(
            $res['data']['rate'],
            $res['data']['from']['currency'] .' '. $res['data']['from']['amount'],
            $res['data']['to']['currency'] .' '. $res['data']['to']['amount']
        );

        $this->table($headers, [$result]);
        $this->info("");

    }
}
