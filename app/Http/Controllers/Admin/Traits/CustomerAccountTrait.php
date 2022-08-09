<?php

namespace App\Http\Controllers\Admin\Traits;

use App\Services\BankOne\CustomerAccount\AccountServices;

trait CustomerAccountTrait
{
    /**
     * Update customer Account in CBA
     *
     * @param $customer
     *
     * @return bool|mixed
     */
    public static function updateCustomerAccount($customer)
    {
        $bioData = $customer->bioData->first();

        $nextOfKin = $customer->nextOfKin->first();

        $datas = [
            'customerID' => $customer->cba_id,
            'LastName' => $customer->last_name,
            'Gender' => $customer->gender === 'Male' ? 0 : 1,
            'OtherNames' => $customer->first_name,
            'Address' => $bioData->address,
            'Email' => $customer->email,
            'PhoneNumber' => $customer->phone,
            'NickName' => $customer->username,
            'BankVerificationNumber' => $bioData->bvn,
            'EmailNotification' => true,
            'PhoneNotification' => true,
            'DateOfBirth' => $bioData->dob,
            'LocalGovernment' => $bioData->city,
            'State' => $bioData->state->name,
            'WorkNextOfKin' => $nextOfKin->name,
            'WorkNextOfKinAddress' => $nextOfKin->address,
            'WorkNextOfKinPhoneNo' => $nextOfKin->phone,
        ];

        return (new AccountServices())->updateCustomerAccount($datas);
    }
}
