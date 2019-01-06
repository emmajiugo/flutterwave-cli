<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\RaveBase;

class TestAccountCommand extends Command
{
    //Add Trait
    use RaveBase {
        RaveBase::__construct as RaveConstruct;
    }

    /**
     * Call parent constructor
     */
    public function __construct()
    {
        parent::__construct();
        self::RaveConstruct();
    }

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'test:account {amount?} {accountbank?} {accountnumber?} {email?} {phone?} {firstname?} {lastname?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run an account charge test.';

    /**
     * initialize inputs
     * @var string
     */
    protected $amount;
    protected $accountbank;
    protected $accountnumber;
    protected $email; protected $firstname;
    protected $phone; protected $lastname;

    /**
     * get input details from user to generate payload
     * @return Array
     */
    protected function getInputDetails()
    {
        $this->amount = $this->argument('amount');
        $this->accountbank = $this->argument('accountbank');
        $this->accountnumber = $this->argument('accountnumber');
        $this->email = $this->argument('email');
        $this->phone = $this->argument('phone');
        $this->firstname = $this->argument('firstname');
        $this->lastname = $this->argument('lastname');

        //ask questions for details
        if (!$this->amount){
            $this->amount = $this->ask('Enter an amount.');
        }
        if (!$this->accountbank){
            $this->accountbank = $this->ask('Enter Bank. (Please enter "044" for Access Bank)');
        }        
        if (!$this->accountnumber){
            $this->accountnumber = $this->ask('Enter Account Number. (Please enter "0690000031")');
        }
        if (!$this->firstname){
            $this->firstname = $this->ask('Enter firstname.');
        }
        if (!$this->lastname){
            $this->lastname = $this->ask('Enter lastname.');
        }
        if (!$this->email){
            $this->email = $this->ask('Enter email.');
        }
        if (!$this->phone){
            $this->phone = $this->ask('Enter phone.');
        }

        //set payload data
        return [
            'PBFPubKey' => $this->pkey,
            'currency' => 'NGN',
            'country' => 'NG',
            'accountbank' => $this->accountbank,
            'accountnumber' => $this->accountnumber,
            "payment_type" =>  "account",
            'amount' => $this->amount,
            'email' => $this->email,
            "phonenumber"=> $this->phone,
            "firstname"=> $this->firstname,
            "lastname"=> $this->lastname,
            'txRef' => time(),
        ];
    }
    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //get inputs from users
        $payload = $this->getInputDetails();

        //charge card
        $res = $this->chargeAccount($payload);
        $flwref = $res['data']['flwRef'];

        //get OTP
        $OTP = $this->ask('Enter OTP');
        $result = $this->getAccountOTP($flwref, $OTP);

        // $this->notify('This is notification', 'Fuck off');
        dd('Transaction Status: '.strtoupper($result['data']['status']));
        // dd($res);
    }
}
