<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class BankBranchCommand extends Command
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
    protected $signature = 'bank:branch {id : the id is returned when listing all banks} {--live : Perform request with live credentials}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List of bank branches in NG, GH, KE, UG, ZA, TZ respectively';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        $prod = $this->option('live');

        $this->sendPayload($id, $prod);
    }

    /**
     * Docs
     */
    public function sendPayload($id, $prod) {

        // processing...
        $this->line($this->processing());

        $endpoint = "banks/" . $id. "/branches";

        $res = $this->HttpGet($endpoint, $prod);

        if ($res['status'] === "error") {
            $this->error($res['message']);
            exit();
        }

        // format output
        $headers = ['ID', 'Bank ID', 'Swift Code', 'BIC', 'Branch Code', 'Branch Name'];

        $result = [];

        foreach ($res['data'] as $value) {
            array_push($result, array(
                $value['id'],
                $value['bank_id'],
                $value['swift_code'],
                $value['bic'],
                $value['branch_code'],
                $value['branch_name']
            ));
        }

        $this->table($headers, $result);
        $this->info("");

    }
}
