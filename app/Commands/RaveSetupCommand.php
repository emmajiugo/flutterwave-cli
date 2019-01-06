<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Filesystem\Filesystem;

class RaveSetupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'rave:setup {skey?} {pkey?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Setup Rave Credentials (Secret & Public Keys)';

    /**
     * initialize keys
     */
    private $skey; private $pkey;
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
        $this->skey = $this->argument('skey');
        $this->pkey = $this->argument('pkey');

        if (!$this->pkey) {
            $this->pkey = $this->ask('Enter Rave Test Public Key');
        }
        if (!$this->skey) {
            $this->skey = $this->ask('Enter Rave Test Secret Key');
        }

        //write keys to .env file
        $path = getcwd().'/.env'; //get path to .env file
        $res = $this->files->isFile($path);

        //check if file exists
        if ($res){
            $content0 = '';
            $content1 = 'SECRET_KEY="'.$this->skey.'"';
            $content2 = 'PUBLIC_KEY="'.$this->pkey.'"';
            //write to the file
            $this->files->put($path, $content0);//clear the content of the file
            $this->files->append($path, $content1."\n");
            $this->files->append($path, $content2."\n");

            return "Hurray! Rave CLI is ready to use.";
        } else {
            return "Oops! You don't have a .env file in your DIR. Please re-install and follow the steps";
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $result = $this->getKeys();
        
        dd($result);
    }
}
