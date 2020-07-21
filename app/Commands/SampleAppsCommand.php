<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class SampleAppsCommand extends Command
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
    protected $signature = 'generate:app {type? : enter your desired sample app}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generates your desired sample app for you.';

    /**
     * initialize inputs
     * @var string
     */
    protected $type;

    /**
     * Get type of sample app
     * @var String
     */
    public function getSampleType()
    {
        $this->type = $this->argument('type');

        //list sample supported
        $this->info("");
        $this->info("SUPPORTED SAMPLE APPS");
        $this->info("========================");
        // $this->info("");

        $this->line("[modal] <comment>Modal sample in Inline, Standard and Quick Setup</comment>");
        $this->line("[charge] <comment>Card and Account Charge Sample App</comment>");
        $this->line("[3dsecure] <comment>3DSecure Payment Sample App</comment>");
        $this->line("[transfers] <comment>Single and Bulk Transfer Sample App</comment>");
        $this->line("[webhook] <comment>Receiving Response in Webhook</comment>");
        $this->line("[splitpayment] <comment>Split Payments to Different Merchants</comment>");
        $this->line("[subscription] <comment>Subscription Payment using Rave</comment>");
        $this->line("[bills_services] <comment>Bills and Services with Rave</comment>");
        $this->line("[extra_info] <comment>Passing Extra Information to Rave</comment>");
        $this->line("[tokenized_charges] <comment>How to Tokenize a Card</comment>");
        $this->line("[preauth_service] <comment>How to use the Preauth Services</comment>");
        $this->line("[db_logging] <comment>How to log your response to DB.</comment>");
        $this->line("");

        //ask questions for details
        if (!$this->type){
            $this->type = $this->ask('Type sample app (choose from the block above)');
        }

        //switch case
        switch ($this->type) {
            case '3dsecure':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case 'charge':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case 'db_logging':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case 'extra_info':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case 'rave_modal':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case 'splitpayment':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case 'tokenized_charges':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case 'transfers':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case 'webhook':
                //run a function here and return 'app created'
                $res = $this->createSampleApp($this->type);
                return $res;
                break;
            case '':
                return 'Oops! You didn\'t select any app.';
                break;

            default:
                return 'Oops! sample app not supported yet.';
                break;
        }
    }

    /**
     * create the sample app for user
     */
    public function createSampleApp($app)
    {
        $source = getcwd().'\\FLW\\'.$app; //source path
        $dest = ''; //destination path

        //ask for destination path
        $path = $this->ask('Enter destination path (eg: C:\xampp\htdocs)');
        $dest = $path.'\\'.$app;

        // Start copying
        $res = $this->xcopy($source, $dest);
        return $res;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = $this->getSampleType();
        dd($result);
    }

}
