<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class TransactionVerifyCommand extends Command
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
    protected $signature = 'transaction:verify {id : the transaction id is the data.id param from the charge response} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Verify transaction to confirm final status';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $trxId = $this->argument('id');
        $prod = $this->option('live');

        $this->sendPayload($trxId, $prod);
    }

    /**
     * Docs
     */
    public function sendPayload($trxId, $prod) {

        // processing...
        $this->line($this->processing());

        $endpoint = "transactions/" . $trxId . "/verify";

        $res = $this->HttpGet($endpoint, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        // format output
        $headers = ['Response', 'Value'];

        $result = array(
            [
                "Status",
                $res['data']['status']
            ],
            [
                "Trx ID",
                $trxId
            ],
            [
                "Email",
                $res['data']['customer']['email']
            ],
            [
                "Currency",
                $res['data']['currency']
            ],
            [
                "Amount",
                $res['data']['amount']
            ]
        );

        $this->table($headers, $result);
        $this->info("");

    }
}
