<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Profile;

class verifyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $families = Profile::whereNotNull('email')->groupBy('email')->get();
        //$leads = Profile::whereNotNull('email')->groupBy('email')->get();

        foreach ($families as $family) {
            if ($this->validEmail($family->email)) {
                $family->email_verified = 1;
                $family->save();
            }
        }
        //leadFamily
        echo "verification done";
    }

    /*
            Validate an email address.
            Provide email address (raw input)
            Returns true if the email address has the email
            address format and the domain exists.
        */
    public function validEmail($email)
    {
        //dd($request->all());
        // $email = $request->email;
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        } else {
            $domain = substr($email, $atIndex + 1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                // local part length exceeded
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen - 1] == '.') {
                // local part starts or ends with '.'
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)) {
                // local part has two consecutive dots
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                // character not valid in domain part
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                // domain part has two consecutive dots
                $isValid = false;
            } else if (!preg_match(
                '/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                str_replace("\\\\", "", $local)
            )) {
                // character not valid in local part unless
                // local part is quoted
                if (!preg_match(
                    '/^"(\\\\"|[^"])+"$/',
                    str_replace("\\\\", "", $local)
                )) {
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain, "MX") ||
                checkdnsrr($domain, "A"))) {
                // domain not found in DNS
                $isValid = false;
            }
        }
        return $isValid;
    }
}
