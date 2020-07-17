<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class CardBinCommand extends Command
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
    protected $signature = 'card:bin {bin : The first 6 six digits on a debit/credit card} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get information about the card';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $bin = $this->argument('bin');
        $prod = $this->option('live');

        $this->sendPayload($bin, $prod);
    }

    /**
     * Payload
     */
    public function sendPayload($bin, $prod) {

        // processing...
        $this->line($this->processing());

        $endpoint = "card-bins/" . $bin;

        $res = $this->HttpGet($endpoint, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        // format output
        $headers = ['Response', 'Value'];

        $result = array(
            [
                "Issuing Country",
                $res['data']['issuing_country']
            ],
            [
                "BIN",
                $res['data']['bin']
            ],
            [
                "Card Type",
                $res['data']['card_type']
            ],
            [
                "Issuer Info",
                $res['data']['issuer_info']
            ]
        );

        $this->table($headers, $result);
        $this->info("");

    }
}
