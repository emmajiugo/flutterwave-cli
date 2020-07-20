<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use App\Traits\FlutterwaveBase;

class DocsCommand extends Command
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
    protected $signature = 'docs {--feature=default : features of our API you are searching for}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Navigate to Flutterwave API documentation';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $option = $this->option('feature');

        $this->docs($option);
    }

    /**
     * Docs
     */
    public function docs($option) {

        switch ($option) {
            case 'card-payment':
            case 'account-payment':
            case 'transfers':
            case 'webhook':
            case 'test-cards':
            case 'test-bank-accounts':
            case 'encryption':
            case 'inline-modal':
            case 'standard-modal':
            case 'payment-links':
            case 'payment-plans':
            case 'bank-transfer':
            case 'uk-account-payment':
            case 'mpesa':
            case 'ussd-payment':
            case 'rwanda-mobile-money':
            case 'uganda-mobile-money':
            case 'zambia-mobile-money':
            case 'ghana-mobile-money':
            case 'split-payments':
            case 'bill-payments':
            case 'bvn-verification':
            case 'account-verification':
            case 'transaction-verification':
            case 'card-bin-verification':
                $url = $this->docReference($option);
                $this->redirect($url);
                break;

            default:
                # show docs available to navigate
                $this->info("");
                $this->info("AVAILABLE FEATURES");
                $this->info("");
                $this->info("NB: Attach the tag <comment>--feature=transfer</comment> to navigate to Transer docs.");
                $this->info("To access other features, use the tags in the blocks below.");
                $this->line("================================================================");

                $this->line("");
                $this->line("[card-payment] - <comment>Card charge</comment>");
                $this->line("[account-payment] - <comment>Account charge</comment>");
                $this->line("[transfers] - <comment>Transfer implementation</comment>");
                $this->line("[webhook] - <comment>Webhook implementation</comment>");
                $this->line("[test-cards] - <comment>Test cards</comment>");
                $this->line("[test-bank-accounts] - <comment>Test bank accounts</comment>");
                $this->line("[encryption] - <comment>Encryption implementation</comment>");
                $this->line("[inline-modal] - <comment>Flutterwave inline modal</comment>");
                $this->line("[standard-modal] - <comment>Flutterwave standard modal</comment>");
                $this->line("[payment-links] - <comment>Payment links</comment>");
                $this->line("[payment-plans] - <comment>Payment plans</comment>");
                $this->line("[bank-transfer] - <comment>Pay with bank transfer</comment>");
                $this->line("[uk-account-payment] - <comment>UK account payment</comment>");
                $this->line("[mpesa] - <comment>Mpesa payment</comment>");
                $this->line("[ussd-payment] - <comment>USSD payment</comment>");
                $this->line("[rwanda-mobile-money] - <comment>Rwanda Mobile Money</comment>");
                $this->line("[uganda-mobile-money] - <comment>Uganda Mobile Money</comment>");
                $this->line("[zambia-mobile-money] - <comment>Zambia Mobile Money</comment>");
                $this->line("[ghana-mobile-money] - <comment>Ghana Mobile Money</comment>");
                $this->line("[split-payments] - <comment>Split payments</comment>");
                $this->line("[bill-payments] - <comment>Bill payments</comment>");
                $this->line("[bvn-verification] - <comment>BVN verification</comment>");
                $this->line("[account-verification] - <comment>Account verification</comment>");
                $this->line("[transaction-verification] - <comment>Transaction verification</comment>");
                $this->line("[card-bin-verification] - <comment>Card BIN verification</comment>");
                $this->line("");
                break;
        }

    }
}
