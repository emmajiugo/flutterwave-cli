<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class BankCommand extends Command
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
    protected $signature = 'bank:list {country : the country of the bank (e.g: NG, GH, KE, UG, ZA or TZ)} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List of banks in NG, GH, KE, UG, ZA, TZ respectively';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $country = $this->argument('country');
        $prod = $this->option('live');

        dd($this->sendPayload($country, $prod));
    }

    /**
     * Docs
     */
    public function sendPayload($country, $prod) {

        // processing...
        $this->line($this->processing());

        $endpoint = "banks/" . $country;

        $res = $this->HttpGet($endpoint, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        // format output
        $headers = ['ID', 'Bank Code', 'Bank Name'];

        $result = [];

        foreach ($res['data'] as $value) {
            array_push($result, array(
                $value['id'],
                $value['code'],
                $value['name']
            ));
        }

        $this->table($headers, $result);
        $this->info("");

    }
}
