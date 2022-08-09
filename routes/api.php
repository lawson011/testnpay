<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Http;
use App\Services\BankOne\Gl\GlService;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\Repositories\BillerCategory\BillerCategoryInterface;
use App\Repositories\Biller\BillerInterface;
use App\Http\Resources\BillsResource;


Route::post('/glservice',function(){

    $data = [
        'NibssCode' => config('bankone.nuture-mfb.nibbs_code'),
        'AccountNumber' => '1100222231',
        'Amount' => 1000,
        'RetrievalReference' => 'ijlkjjldkjklj',
        'Narration' => 'Transfer to this person is done',
        'GLCode' => '1017',
        'Token' => '3508A076-9A36-4562-9614-B551A390F11E',
    ];
    $response = Http::post('http://52.168.85.231/ThirdPartyAPIService/APIService/Transactions/Debit',$data);

    return dd($response->status());
});



Route::middleware(["throttle:10|60,1","httpLogger","allowedApp"])->group(static function() {

        Route::prefix("v1/customer")->group(static function(){
        Route::namespace("V1\Auth")->group(static function(){
            Route::prefix("reset-password")->group(static function () {
                Route::post("/request", 'ResetPasswordController@requestResetForgotPassword');
                Route::post("/", 'ResetPasswordController@resetPassword')->name('password.reset');
            });

            Route::post('device/detach','CustomerDeviceController@requestDetach');
            Route::post('device/add','CustomerDeviceController@detachDevice');

            Route::post("/login", 'LoginController@index');
            Route::get('/login', 'LoginController@unauthenticatedResponse')->name('login');
            Route::post("/refresh-token", 'LoginController@refreshToken');

            Route::prefix("registration")->group(static function(){
                Route::post("/verify-bvn", 'RegistrationController@verifyBvn');
                Route::post("/verify-phone-otp", 'RegistrationController@verifyPhoneOtp');
                Route::get("/state", 'RegistrationController@getState');
                Route::post("/basic-form-step-one-validation", 'RegistrationController@basicFormStepOneValidation');
                Route::post("/basic-form-step-two-validation", 'RegistrationController@basicFormStepTwoValidation');
                Route::post("/create", 'RegistrationController@create');

                Route::prefix("existing-account")->group(static function(){
                    Route::post("/verify-account-number", 'RegistrationController@verifyAccountNumber');
                    Route::post("/verify-otp-code", 'RegistrationController@verifyOtpCode');
                    Route::post("/create", 'RegistrationController@createCustomerUsingAccountNumber');
                });

            });

            Route::prefix("web")->group(static function(){
                Route::post("/login/otp-code", 'LoginController@webLoginOtpCode');
                Route::post("/login", 'LoginController@webLogin');
                Route::post("/basic-form-step-one-validation", 'RegistrationController@webBasicFormStepTwoValidation');
            });

        });

        //version api
        Route::prefix("version")->group(static function(){
//            Route::post('add','V1\VersionController@add');
            Route::get('/','V1\VersionController@getVersion');
        });
    });

    //Endpoint for only login user
    Route::group(['middleware' => ['auth:api','checkUser']], static function () {

        Route::get('/', function () {
            return response()->json('welcome');
        });

        Route::prefix('v1/customer')->group(static function (){

            Route::get('advert', 'Admin\AdvertController@active');

            Route::put('active', 'V1\CustomerController@active');

            Route::post('guarantor', 'V1\CustomerGuarantorController@store');

            Route::middleware(["staffMiddleware"])->group(static function() {
                Route::prefix('staff')->group(static function (){
                    Route::post('/change-passport', 'V1\StaffController@changePassport');
                });
            });

            Route::prefix('onboarding')->group(static function (){
                Route::post('customer', 'V1\CustomerOnboardingCustomerController@store');
            });

            Route::get('/details', 'V1\CustomerController@index');

            Route::get('/savings-current-accounts', 'V1\CustomerController@customerSavingsOrCurrentAccounts');

            Route::get('/accounts', 'V1\CustomerController@customerAccounts');

            Route::put('/terms-condition', 'V1\CustomerController@termsCondition');

            Route::post('/card-request', 'V1\CustomerController@requestForCard');

            Route::put('/update-bvn', 'V1\CustomerController@updateBVN');

            Route::prefix("utility")->group(static function(){
                Route::get("/list", 'V1\CustomerController@listUtility');
                Route::post("/upload", 'V1\CustomerController@uploadUtility');
            });

            Route::prefix("identity-card")->group(static function(){
                Route::get("/list", 'V1\CustomerController@listIdentityCard');
                Route::post("/upload", 'V1\CustomerController@uploadIdentityCard');
            });

            Route::prefix("signature")->group(static function(){
                Route::get("", 'V1\CustomerController@signature');
                Route::post("", 'V1\CustomerController@uploadSignature');
            });

            Route::prefix('loan')->group(static function (){
                Route::post('/apply', 'V1\LoanController@apply');
                Route::get('/history', 'V1\LoanController@loanHistory');
                Route::get('/setting', 'V1\LoanController@loanSetting');
            });

            Route::prefix('pin')->group(static function (){
                Route::post('/create','V1\PinController@create');
                Route::post('/update','V1\PinController@update');
                Route::post('/validate','V1\PinController@validatePin');
            });

            Route::prefix('banks')->group(static function(){
                Route::get('/','V1\BankController@index');
                Route::post('/','V1\BankController@find');
            });

            Route::prefix('account')->group(static function(){
                Route::get('/me','V1\AccountEnquiryController@me');
                Route::post('/query','V1\AccountEnquiryController@query');
                Route::post('/query/nip','V1\AccountEnquiryController@nipQuery');
            });

            Route::prefix('token')->group(static function(){
                Route::get('/generate','V1\TokenController@generateToken');
                Route::post('/validate','V1\TokenController@validateToken');
            });

            Route::prefix('transfer')->group(static function(){
                Route::post('/local','V1\TransferController@localTransfer');
                Route::post('/nip/interbank','V1\TransferController@interBankTransfer');
            });

            Route::prefix('bills')->group(static function(){
                Route::get('/','V1\BillController@index');
                Route::post('/verify','V1\BillController@verify');
                Route::post('/pay','V1\BillController@pay');
            });

            Route::prefix('history')->group(static function(){
                Route::get('/all','V1\TransactionHistoryController@all');
                Route::post('/find','V1\TransactionHistoryController@find');
                Route::post('/range','V1\TransactionHistoryController@range');
            });

            Route::prefix('fixed-account')->group(static function(){
                Route::post('/create','V1\FixedAccountController@create');
                Route::get('/settings','V1\FixedAccountController@settings');
                Route::get('/history','V1\FixedAccountController@history');
            });

            Route::prefix('beneficiary')->group(static function(){
                Route::get('/','V1\BeneficiaryController@index');
                Route::post('/create','V1\BeneficiaryController@create');
                Route::post('/delete','V1\BeneficiaryController@delete');
            });

            Route::get('/logout', 'V1\Auth\LoginController@logout');
        });
    });

});

Route::middleware(["httpLogger","thirdPartyApp"])->group(static function() {
    Route::prefix("thirdParty")->group(static function() {
        Route::prefix("referral")->group(static function() {
            Route::namespace("ThirdParty\Referral")->group(static function () {
                Route::post('/', 'CustomerController@getAll');
                Route::get('customer-details', 'CustomerController@customerDetails');
                Route::get('/{code}', 'CustomerController@getByReferralCode');
            });
        });
    });

    //endpoint for referral
});
