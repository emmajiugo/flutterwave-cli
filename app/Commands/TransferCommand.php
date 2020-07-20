<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class TransferCommand extends Command
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
    protected $signature = 'transfer {amount?} {accountbank?} {accountnumber?} {narration?} {currency?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run a transfer.';

    /**
     * initialize inputs
     * @var string
     */
    protected $amount;
    protected $accountbank;
    protected $accountnumber;
    protected $narration;
    protected $currency;

    /**
     * get input details from user to generate payload
     * @return Array
     */
    protected function getInputDetails()
    {
        $this->amount = $this->argument('amount');
        $this->accountbank = $this->argument('accountbank');
        $this->accountnumber = $this->argument('accountnumber');
        $this->narration = $this->argument('narration');
        $this->currency = $this->argument('currency');

        //ask questions for details
        if (!$this->amount){
            $this->amount = $this->ask('Enter amount you want to transfer.');
        }
        if (!$this->currency){
            $this->currency = $this->ask('Enter Currency. (Please enter "NGN")');
        }
        if (!$this->accountbank){
            $this->accountbank = $this->ask('Enter Bank for transfer. (Please enter "044" for Access Bank)');
        }
        if (!$this->accountnumber){
            $this->accountnumber = $this->ask('Enter Account Number. (Please enter "0690000031")');
        }
        if (!$this->narration){
            $this->narration = $this->ask('Enter Narration.');
        }

        //set payload data
        return [
            "account_bank" => $this->accountbank,
            "account_number" => $this->accountnumber,
            "amount" => $this->amount,
            "seckey" => $this->skey,
            "narration" => $this->narration,
            "currency" => $this->currency,
            "reference" => time(),
        ];
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

    }
}
