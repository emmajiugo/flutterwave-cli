<p align="center">
    <img title="Flutterwave" height="200" src="https://cdn-images-1.medium.com/max/2000/1*9Ns-5XIj1xgGFgZ8t_KkZw.png" />
</p>

## Flutterwave PHP CLI
The Flutterwave CLI application helps you tests some features of Flutterwave on the go and also generate sample applications that shows you the simplest and easy way of how these features work and how to implement them into your own application or platform.

## Usage:
1. Clone the Repo to your local machine.
2. `cd` into the directory and run `composer install` to install dependencies.
3. Type in `php flutterwave` to view the available commands.
4. To test `card charge` on the terminal, run `php flutterwave test:card`
5. To test `account charge` on the terminal, run `php flutterwave test:account`
6. To test `transfer` on the terminal, run `php flutterwave test:transfer`
7. To generate sample apps, run `php flutterwave generate:sampleapp`. This command will list all available or future support of sample apps. But because we are still in the development mode, the sample apps supported are:
- `3DSecure`
- `Charge`
- `DB Logging`
- `Extra-Info` i.e. passing extra information to Flutterwave
- `Flutterwave Modal` i.e. Inline, Standard setup
- `Split Payment`
- `Tokenized Charges`
- `Transfers`
- `Webhook`


Test and give me your feedback.  
Chigbo Ezejiugo  
emmajiugo@gmail.com
