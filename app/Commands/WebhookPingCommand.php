<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class WebhookPingCommand extends Command
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
    protected $signature = 'webhook:ping {id : the transaction id from data.id} {url : your webhook url for testing purpose} {--event= : Event should be transfer|card} {--hash= : A test secret hash} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Ping test webhook response to your URL';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $trxId = $this->argument('id');
        $webhookUrl = $this->argument('url');
        $event = $this->option('event');
        $hash = $this->option('hash') ?? "";
        $prod = $this->option('live');

        if ($event === null) {
            $this->error("\nEvent is required. --event=card|transfer\n");
            exit();
        }

        $this->sendPayload($trxId, $webhookUrl, $event, $hash, $prod);
    }

    /**
     * Docs
     */
    public function sendPayload($trxId, $webhookUrl, $event, $hash, $prod) {

        // processing...
        $this->line($this->processing());

        if ($event === "card") {
            $endpoint = "transactions/" . $trxId . "/verify";
        } else if ($event === "transfer") {
            $endpoint = "transfers/" . $trxId;
        } else {
            $this->error("Event provided is wrong. --event=card|transfer\n");
            exit();
        }

        // return verify transaction/get transfer response
        $res = $this->HttpGet($endpoint, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        $data = ($event === "card") ? $this->cardWebhookResponse($res['data']) : $this->transferWebhookResponse($res['data']);

        $hookStatus = $this->webhookPing($webhookUrl, $data, $hash);

        // return $hookStatus;

        if ($hookStatus === 200) {
            $this->info("Test Webhook response pinged to your provided URL.");
        } else {
            $this->error("An error occurred. Please ensure that the url provided is a valid one.");
        }

    }
}
