<?php

use App\Models\BvnSession;
use App\Models\Customers\CustomerActivityLog;
use App\Services\BankOne\BankOneUtilServices;
use Carbon\Carbon;
use App\Models\Customers\CustomerDevice;
use Illuminate\Support\Facades\Auth;
use App\Models\UtilityType;
use App\Models\IdentityCardType;
use App\Models\Loans\LoanSetting;
use App\Models\Loans\LoanStatus;
use App\Services\BankOne\ThirdPartyApiService\Account\AccountEnquiryService;
use App\Models\Customers\CardRequestStatus;
use Illuminate\Support\Str;
use App\Services\BankOne\Gl\GlService;
use App\Services\BankOne\ThirdPartyApiService\BankService;
use App\Services\BankOne\ThirdPartyApiService\Transfer\LocalTransferService;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


function bvnSessionStore(array $params)
{
    $bvn = new BvnSession();
    $bvn->bvn_number = $params['bvn_number'];
    $bvn->bvn_attributes = json_encode($params['bvn_attributes']);
    $bvn->save();
    return $bvn;
}

function bvnSessionDelete(int $bvnNo)
{
    return BvnSession::where(['bvn_number' => $bvnNo])->delete();
}

function bvnSessionGet(int $bvnNo)
{
    return BvnSession::where(['bvn_number' => $bvnNo])->first();
}

function getLoanSettingByColumn(array $params)
{
    return LoanSetting::where($params);
}

function getLoanSetting()
{
    return LoanSetting::get();
}

function getAllUtilityType()
{
    return UtilityType::get();
}

function getAllIdentityCardType()
{
    return IdentityCardType::get();
}

function createActivityLog(array $params)
{

    $model = new CustomerActivityLog();
    $user = Auth::user();
    $model->customer_id = $user == null ? null : $user->id;
    $model->ip = $params['ip'];
    $model->action = $params['action'];
    $model->uri = $params['uri'];
    $model->body = $params['body'];
    $model->message_type = $params['error'] == true ? 'critical' : 'info';
    $model->platform = Request()->header("Platform");
    $model->created_at = Carbon::now();
    $model->save();

    return $model;
}

function getCustomerDeviceByColumn(array $params)
{
    return CustomerDevice::where($params);
}

function storeCustomerDevice(array $params)
{
    CustomerDevice::where('customer_id', $params['customer_id'])->update([
        'active' => false
    ]);
    $model = new CustomerDevice();
    $model->customer_id = $params['customer_id'];
    $model->device_name = $params['device_name'];
    $model->device_id = $params['device_id'];
    $model->active = true;
    $model->save();
}

function blockCustomerStatus(array $params)
{

    $model = new \App\Models\Customers\CustomerBlockStatus();
    $model->customer_id = $params['customer_id'];
    $model->reason = $params['reason'];
    $model->status = $params['status'];
    $model->user_id = Auth::id();
    $model->save();
}

function usernameToSkipDeviceDetach($username)
{
    $username = \App\Models\Customers\UsernameToSkipDetachDevice::where([
        ['username', '=', $username],
        ['active', '=', true]
    ])->first();

    if ($username) {
        return $username->username;
    }

    return null;
}

function formatLogResponse($params)
{
    $user = Auth::user();
    return [
        'name' => $user->full_name ?? '',
        'email' => $user->email ?? '',
        'phone' => $user->phone ?? '',
        'data' => $params
    ];
}

function formatBvnDetails($params)
{
    return [
        'first_name' => $params->first_name ?? $params['first_name'],
        'last_name' => $params->last_name ?? $params['last_name'],
        'dob' => $params->dob ?? $params['dob'],
    ];
}

function formatDate($date)
{
    return \Illuminate\Support\Carbon::parse($date);
}

function getUniqueToken($length = 6)
{
    return substr(random_int(100000, 9999999), 0, $length);
}

function generateCustomerReferral()
{
    $ref = strtolower(Str::random(4) . getUniqueToken(2));
    $checkRef = \App\Models\Customers\Customer::where('referral_code', $ref)->exists();
    if ($checkRef) {
        return generateCustomerReferral();
    }
    return $ref;
}

function loanStatusById(int $id)
{
    return LoanStatus::find($id);
}

function loanStatusByName(string $name)
{
    return LoanStatus::whereName($name)->first();
}

function cardRequestStatusByName(string $name)
{
    return CardRequestStatus::whereName($name)->first();
}

function cardRequestStatusById(int $id)
{
    return CardRequestStatus::find($id);
}

function getLoanStatus($name)
{
    return LoanStatus::where('name', '!=', $name)->get();
}

if (!function_exists('allRoles')) {

    function allRoles()
    {
        return Role::all();
    }
}

if (!function_exists('getUserAccountDetails')) {

    function getUserAccountDetails($body)
    {
        return app(AccountEnquiryService::class)->getSpecificAccountInformation([
            'account_number' => $body['account_number']
        ], null);
    }
}

if (!function_exists('getAllPermissions')) {
    function getAllPermissions()
    {
        return Permission::latest()->get();
    }
}

if (!function_exists('getRoleByName')) {
    function getRoleByName(string $name)
    {
        return Role::where('name', $name)->first();
    }
}

function sendSms($body)
{
    return app(BankOneUtilServices::class)->sendSms($body);
}

if (!function_exists('auditTray')) {
    function auditTray($reason, $oldParams, $newParams)
    {
        $model = new \App\Models\AuditTray();
        $model->reason = $reason;
        $model->new_payload = json_encode($newParams);
        $model->old_payload = json_encode($oldParams);
        $model->user_id = Auth::id();
        $model->save();
    }
}

if (!function_exists('convertSoapToArray')) {
    function convertSoapToArray($result)
    {
        $content = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);
        $xml = new SimpleXMLElement($content);

        return json_decode(
            json_encode((array)$xml->xpath('//SBody')[0]), TRUE
        );
    }
}


if (!function_exists('debitGl')) {
    /**
     * @param $action
     * @param $url
     * @param $data
     * @return mixed
     */
    function debitGl($data)
    {
        return app(GlService::class)->debit($data);
    }
}

if (!function_exists('getSpecificAccountInformation')) {
    /**
     * @param $account_number
     * @param $field
     * @return mixed
     */
    function getSpecificAccountInformation($account_number, $field)
    {
        return app(AccountEnquiryService::class)->getSpecificAccountInformation([
            'account_number' => $account_number
        ], $field);
    }
}

if (!function_exists('getBankByCode')) {
    /**
     * @param $account_number
     * @param $field
     * @return mixed
     */
    function getBankByCode($code)
    {
        $banks = app(BankService::class)->bankOneAllBanks('data');

        foreach ($banks as $bank) {
            if ($bank['Code'] === $code) {
                return $bank['Name'];
            }
        }

        return null;
    }
}

function xml_to_array($data)
{
    $xml = simplexml_load_string($data);

    return json_decode(json_encode($xml), true);
}

function dataLogger($data)
{
    logger()->info($data['statement']);
    logger()->info($data['content']);
}

function localTransfer($data)
{
    return app(LocalTransferService::class)->send($data);
}


