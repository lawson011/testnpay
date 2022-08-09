<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain(env('ADMIN_APP_URL'))->middleware(["throttle:10|60,1","adminActivityLog"])->group(function () {

    Route::middleware(['guest'])->group(function () {

        Route::get('/', 'Admin\Auth\LoginController@showLoginForm')->name('login');

        Route::post('npay-admin', 'Admin\Auth\LoginController@login')->name('nuturePayLogin');

        Route::get('change-password/{token}', 'Admin\UserController@changePassword');

        Route::post('password-reset/{token}', 'Admin\UserController@passwordReset')->name('password.reset');
    });

    Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function () {

        Route::middleware(['auth', 'role:super-admin|admin|loan-officer'])->group(function () {

            Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

            Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

            Route::prefix('/log')->group(function () {
                Route::get('/activities', 'LogController@activities')->name('log.activities');
            });

            Route::get('/loanstatistics', 'DashboardController@loanStatistics');

            Route::prefix('profile')->group(function () {
                Route::get('/', 'ProfileController@index')->name('profile');
                Route::get('/sendmail', 'ProfileController@sendPasswordMail');
                Route::get('/changepassword', 'ProfileController@changePassword')->name('changePassword');
                Route::post('/changepassword', 'ProfileController@savePassword')->name('savePassword');
            });

            Route::prefix('role')->group(function () {
                Route::get('/', 'RoleController@index')
                    ->middleware(['permission:super-admin|view roles'])->name('role.index');

                Route::post('/update/{id}', 'RoleController@update')
                    ->middleware(['role_or_permission:super-admin|update role permission'])->name('role.update');

                Route::post('create', 'RoleController@create')
                    ->middleware(['permission:super-admin|create role'])->name('role.create');

                Route::get('show', 'RoleController@show')
                    ->middleware(['permission:super-admin|create role'])->name('role.show');
            });

            Route::prefix('user')->group(function () {
                Route::get('/', 'UserController@index')
                    ->middleware(['role_or_permission:super-admin|all admins'])->name('user.index');

                Route::post('/{id}/edit', 'UserController@edit')
                    ->middleware(['role_or_permission:super-admin|edit admin'])->name('user.edit');

                Route::post('/update/{id}', 'UserController@update')
                    ->middleware(['role_or_permission:super-admin|edit admin'])->name('user.update');

                Route::post('/create', 'UserController@create')
                    ->middleware(['role_or_permission:super-admin|create admin'])->name('user.create');

                Route::get('reset-password/{id}', 'UserController@resetPassword')
                    ->middleware(['role_or_permission:super-admin|create admin'])->name('user.reset-password');

                Route::put('/block', 'UserController@block')
                    ->middleware(['role_or_permission:super-admin|block admin'])->name('user.block');
                Route::put('/unblock', 'UserController@unblock')
                    ->middleware(['role_or_permission:super-admin|unblock admin'])->name('user.unblock');
            });

            Route::prefix('loan')->group(function () {
                    Route::post('/update-status', 'LoanStatusController@updateStatus')
                        ->name('loan.update-status')->middleware([
                            'role_or_permission:update loans status'
                        ]);
                    Route::get('/awaiting-approval', 'LoanStatusController@awaitingApproval')
                        ->name('loan.awaiting-approval')->middleware([
                            'role_or_permission:view loan awaiting approval'
                        ]);
                    Route::get('/approved', 'LoanStatusController@approved')
                        ->name('loan.approved')->middleware([
                            'role_or_permission:view approve loans'
                        ]);
                    Route::get('/declined', 'LoanStatusController@declined')
                        ->name('loan.declined')->middleware(
                            'role_or_permission:view declined loans'
                        );
         //       });

                Route::get('/settings', 'LoanSettingController@settings')->name('loan.settings');
                Route::get('/settings/update/{id}', 'LoanSettingController@updateView')->name('loan.settings.updateview');
                Route::put('/settings/update', 'LoanSettingController@update')->name('loan.settings.update');
                Route::get('/settings/add', 'LoanSettingController@addView')->name('loan.settings.addview');
                Route::post('/settings/add', 'LoanSettingController@add')->name('loan.settings.add');
            });

            Route::prefix('customer')->group(function () {
                Route::get('/details/{id}', 'CustomerController@customerDetails')->name('customer.details');
                Route::get('/all', 'CustomerController@allCustomers')->name('customer.all');
                Route::put('/block', 'CustomerController@block')
                    ->name('customer.block')->middleware(
                        'role_or_permission:block customer'
                    );
                Route::put('/unblock', 'CustomerController@unblock')
                    ->name('customer.unblock')->middleware(
                        'role_or_permission:unblock customer'
                    );
                Route::put('/is-staff', 'CustomerController@isStaff')
                    ->name('customer.is_staff')->middleware(
                        'role_or_permission:update staff status'
                    );;
                Route::put('/is-not-staff', 'CustomerController@isNotAStaff')
                    ->name('customer.is_not_staff')->middleware(
                        'role_or_permission:update staff status'
                    );
                Route::put('/is-agent', 'CustomerController@isAgent')->name('customer.is_agent')
                    ->middleware('role_or_permission:update agent status');
                Route::put('/is-not-agent', 'CustomerController@isNotAgent')->name('customer.is_not_agent')
                    ->middleware('role_or_permission:update agent status');
                Route::put('/sync', 'CustomerController@syncAccount')
                    ->name('customer.info-sync')->middleware(
                        'role_or_permission:sync customer info with CBA'
                    );
            });

            Route::prefix('registration')->group(function () {
                Route::get('/settings', 'RegistrationSettingController@settings')->name('registration.settings');
                Route::get('/settings-new', 'RegistrationSettingController@newSettings')->name('registration.settings-new');
                Route::post('/settings-new', 'RegistrationSettingController@addNewSettings')->name('registration.settings-new');
                Route::get('/settings-active', 'RegistrationSettingController@activeSettings')->name('registration.settings-active');
            });

            Route::prefix('fixed-deposit')->group(function () {
                Route::get('/settings/new', 'FixedDepositSettingController@newSetting')->name('fixed-deposit.new-setting');
                Route::post('/settings/new', 'FixedDepositSettingController@storeNewSetting')->name('fixed-deposit.store-new-setting');
                Route::get('/settings', 'FixedDepositSettingController@showSettings')->name('fixed-deposit.settings');
                Route::get('/settings/update/{id}', 'FixedDepositSettingController@update')->name('fixed-deposit.settings.update');
                Route::put('/settings/update', 'FixedDepositSettingController@storeUpdate')->name('fixed-deposit.settings.store-update');
            });

            Route::group(['prefix' => 'otp'], static function () {
                Route::get('', 'OTPController@index')
                   ->middleware(['role_or_permission:super-admin'])->name('otp');
                Route::get('delete/{id}', 'OTPController@destroy')->name('otp.delete');
            });

            Route::prefix('advert')->group(function () {
                Route::get('/', 'AdvertController@index')
                    ->name('advert');
                Route::get('create', 'AdvertController@create')
                    ->name('advert.create');
                Route::post('store', 'AdvertController@store')
                    ->name('advert.store');
                Route::get('/update/{id}', 'AdvertController@update')
                    ->name('advert.update');
            });

            Route::prefix('bills')->group(function(){
                Route::get('/', 'BillController@index')->name('bills');
            });

        });

    });

});

Route::get('/', function () {
    return response()->json(
        'NPay'
    );
});
