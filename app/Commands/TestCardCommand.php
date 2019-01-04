<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\RaveBase;

class TestCardCommand extends Command
{
    use RaveBase;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'test:card {amount?} {cardno?} {pin?} {cvv?} {expiryyear?} {expirymonth?} {email?} {phone?} {firstname?} {lastname?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run a card charge test.';

    /**
     * initialie inputs
     * @var string
     */
    protected $amount; protected $expiryyear;
    protected $cardno; protected $expirymonth;
    protected $pin; protected $email; protected $firstname;
    protected $cvv; protected $phone; protected $lastname;

    /**
     * get input details from user to generate payload
     * @return Array
     */
    protected function getInputDetails()
    {
        $this->amount = $this->argument('amount');
        $this->cardno = $this->argument('cardno');
        $this->pin = $this->argument('pin');
        $this->cvv = $this->argument('cvv');
        $this->expiryyear = $this->argument('expiryyear');
        $this->expirymonth = $this->argument('expirymonth');
        $this->email = $this->argument('email');
        $this->phone = $this->argument('phone');
        $this->firstname = $this->argument('firstname');
        $this->lastname = $this->argument('lastname');

        //ask questions for details
        if (!$this->amount){
            $this->amount = $this->ask('Enter an amount.');
        }
        if (!$this->cardno){
            $this->cardno = $this->ask('Enter test card number.');
        }        
        if (!$this->expirymonth){
            $this->expirymonth = $this->ask('Enter a test card expirymonth.');
        }
        if (!$this->expiryyear){
            $this->expiryyear = $this->ask('Enter a test card expiryyear.');
        }
        if (!$this->cvv){
            $this->cvv = $this->ask('Enter a test card cvv.');
        }
        if (!$this->pin){
            $this->pin = $this->ask('Enter a test card pin.');
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
            'PBFPubKey' => 'FLWPUBK-56e4a2c6c9a6b58364bfd07fc1993e2c-X',
            'cardno' => $this->cardno,
            'currency' => 'NGN',
            'country' => 'NG',
            'cvv' => $this->cvv,
            'pin' => $this->pin,
            'suggested_auth' => 'PIN',
            'amount' => $this->amount,
            'expiryyear' => $this->expiryyear,
            'expirymonth' => $this->expirymonth,
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
        $res = $this->chargeCard($payload);
        $flwref = $res['data']['flwRef'];

        //get OTP
        $OTP = $this->ask('Enter OTP');
        $result = $this->getOTP($flwref, $OTP);

        // $this->notify('This is notification', 'Fuck off');
        dd('Transaction Status: '.strtoupper($result['data']['data']['responsemessage'])); 
    }
}
