<?php

namespace App\Commands;

use Storage;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Filesystem\Filesystem;

class FlutterwaveSetupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'setup {testSecretKey? : Test Secret Key from your Flutterwave dashboard} {testPublicKey? : Test Public Key from your Flutterwave dashboard} {liveSecretKey? : Live Secret Key from your Flutterwave dashboard} {livePublicKey? : Live Public Key from your Flutterwave dashboard}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Setup Flutterwave credentials (secret & public keys)';

    /**
     * initialize keys
     */
    private $liveSecretKey;
    // private $livePublicKey;
    private $testSecretKey;
    // private $testPublicKey;
    protected $files;

    /**
     * including Filesystem so we can write on a file
     */
    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->files = $file;
    }

    /**
     * Get keys from user
     */
    protected function getKeys()
    {
        $this->liveSecretKey = $this->argument('liveSecretKey');
        $this->livePublicKey = $this->argument('livePublicKey');
        $this->testSecretKey = $this->argument('testSecretKey');
        $this->testPublicKey = $this->argument('testPublicKey');

        $this->info("");
        $this->info("FLUTTERWAVE CLI SETUP");
        $this->info("This is a one time setup and necessary for efficient use of the Flutterwave CLI. \nNote, your keys are stored in your system and no other place.)");
        $this->comment("==================================================================================");

        if ($this->confirm('Do you wish to continue?')) {

            // if (!$this->testPublicKey) {
            //     $this->testPublicKey = $this->ask('Enter Flutterwave Test Public Key');
            // }
            if (!$this->testSecretKey) {
                $this->testSecretKey = $this->ask('Enter Flutterwave Test Secret Key');
            }
            // if (!$this->livePublicKey) {
            //     $this->livePublicKey = $this->ask('Enter Flutterwave Live Public Key');
            // }
            if (!$this->liveSecretKey) {
                $this->liveSecretKey = $this->ask('Enter Flutterwave Live Secret Key');
            }

        } else {

            $this->comment("Oops! Don't leave me! Don't leave me!!.");
            exit();

        }

        //write keys to .env file
        // $path = getcwd().'/.env'; //get path to .env file
        // $res = $this->files->isFile($path);

        //check if file exists
        // if (!$res){

            // create the .env file if it does not exist
            // $filename = '.env';
            // $handle = fopen($filename, 'w') or die('cannot open the file');
            // fclose($handle);
        // }

        // $content0 = '';
        // $content1 = 'TEST_SECRET_KEY="'. $this->testSecretKey .'"';
        // $content2 = 'TEST_PUBLIC_KEY="'. $this->testPublicKey .'"';
        // $content3 = 'LIVE_SECRET_KEY="'. $this->liveSecretKey .'"';
        // $content4 = 'LIVE_PUBLIC_KEY="'. $this->livePublicKey .'"';

        // //write to the file
        // $this->files->put($path, $content0);//clear the content of the file
        // $this->files->append($path, $content1."\n");
        // $this->files->append($path, $content2."\n");
        // $this->files->append($path, $content3."\n");
        // $this->files->append($path, $content4."\n");

        $content = "TEST_SECRET_KEY=". $this->testSecretKey ."\n";
        $content .= "LIVE_SECRET_KEY=". $this->liveSecretKey;

        if (Storage::put(".env", $content) && Storage::put("builds/.env", $content) ) {

            $this->comment('Hurray! Flutterwave CLI is ready to use.');
        } else {
            $this->error('Oops! something went wrong. Please contact support on this.');
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getKeys();
        // $result = $this->getKeys();

        // dd($result);
    }
}
