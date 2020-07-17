<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class WebhookResendCommand extends Command
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
    protected $signature = 'webhook:resend {id : This is the transaction unique identifier. It is returned in the initiate transaction call as data.id} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Resend a failed transaction webhook to your server';

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

        $endpoint = "transactions/" . $trxId . "/resend-hook";

        $data = array();

        $res = $this->HttpPost($endpoint, $data, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        $this->info(strtoupper($res['message']));
        $this->info("");

    }
}
