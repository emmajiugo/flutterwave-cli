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
    // FIX 2 COMMAND
    // protected $signature = 'setup {testSecretKey? : Test Secret Key from your Flutterwave dashboard} {liveSecretKey? : Live Secret Key from your Flutterwave dashboard} {--mac : Configuration for MAC users} {--windows : Configuration for WINDOWS users}';

    // FIX 1 COMMAND
    protected $signature = 'setup {testSecretKey? : Test Secret Key from your Flutterwave dashboard} {liveSecretKey? : Live Secret Key from your Flutterwave dashboard} {--mac : Configuration for MAC users} {--windows : Configuration for WINDOWS users}';

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
    private $testSecretKey;
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
        $this->testSecretKey = $this->argument('testSecretKey');

        // check if OS is passed
        // $mac = $this->option('mac');
        // $windows = $this->option('windows');

        // if (!$mac && !$windows) {
        //     $this->info("\nPlease pass the tag <comment>--mac</comment> (for MAC users) or <comment>--windows</comment> (for WINDOWS users) to allow us setup your ENV variables.\n");
        //     exit();
        // }

        $this->info("");
        $this->info("FLUTTERWAVE CLI SETUP");
        $this->info("This is a one time setup and necessary for efficient use of the Flutterwave CLI. \nNote, your keys are stored in your system and no other place.)");
        $this->comment("==================================================================================");

        if ($this->confirm('Do you wish to continue?')) {

            if (!$this->testSecretKey) {
                $this->testSecretKey = $this->ask('Enter Flutterwave Test Secret Key');
            }
            if (!$this->liveSecretKey) {
                $this->liveSecretKey = $this->ask('Enter Flutterwave Live Secret Key');
            }

        } else {

            $this->comment("Oops! Don't leave me! Don't leave me!!.");
            exit();

        }

        // SETUP FIX 2
        // $externalCommand = "echo 'TEST_SECRET_KEY=".$this->testSecretKey."\nLIVE_SECRET_KEY=".$this->liveSecretKey."'";

        // if ($mac) {
        //     $os = " >~/.composer/vendor/emmajiugo/flutterwave-cli/builds/.env";
        // } else if ($windows) {
        //     $os = " >%APPDATA%\\Composer\\vendor\\emmajiugo\\flutterwave-cli\\builds\\.env";
        // }

        // try {

        //     exec($externalCommand . $os, $output, $return_var);

        //     if ($return_var > 0) {
        //         $this->error('Oops! something went wrong. Please contact support on this.');
        //     } else {
        //         $this->comment('Hurray! Flutterwave CLI is ready to use.');
        //     }

        // } catch (\Throwable $th) {
        //     $this->error('Oops! something went wrong. Please contact support on this.');
        // }


        // SETUP FIX 1
        $content = $this->testSecretKey .",". $this->liveSecretKey;

        if (Storage::put("FLW-CLI/env.php", $content)) {
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
