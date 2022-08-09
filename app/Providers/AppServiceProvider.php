<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->models() as $model){
            $this->app->bind(
                "App\\Repositories\\$model\\$model" . "Interface",
                "App\\Repositories\\$model\\$model" . "Repository"
            );
        }
    }

    /**
     * Create array of models with repositories and interfaces
     *
     * @return array
     */
    public function models ()
    {

        return [
            'AdminBlockStatus',
            'Advert',
            'Auth',
            'Beneficiary',
            'CardRequest',
            'CustomerActivityLog',
            'CustomerAuth',
            'CustomerOnboardingCustomer',
            'CustomerBioData',
            'CustomerBlockStatus',
            'CustomerCard',
            'CustomerDevice',
            'CustomerIdentityCard',
            'CustomerLoanStatus',
           'CustomerNextOfKin',
            'CustomerRegistrationSetting',
           'CustomerUtility',
            'FixedAccount',
            'FixedAccountSetting',
            'Loan',
            'LoanServiceCharge',
            'LoanSetting',
            'LoanStatus',
            'OtpCode',
            'Transaction',
            'VerifyEmail',
            'Version',
            'Biller',
            'BillerCategory',
            'BillTransaction'
        ];
    }
}
