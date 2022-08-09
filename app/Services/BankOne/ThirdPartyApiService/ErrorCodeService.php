<?php


namespace App\Services\BankOne\ThirdPartyApiService;


use App\Services\ResponseService;
use GuzzleHttp\Client;

class ErrorCodeService
{
    public $errorCode = [
        '00' => 'Approved by Financial Institution',
        '01' => 'Refer to Financial Institution',
        '02' => 'Refer to Financial Institution, Special Condition',
        '03' => 'Invalid Merchant',
        '04' => 'Pick-up card',
        '05'  => 'Do Not Honor',
        '06' => 'Error',
        '07' => 'Pick-Up Card, Special Condition',
        '08' => 'Honor with Identification',
        '09' => 'Request in Progress',
        '10' => 'Approved by Financial Institution, Partial',
        '11' => 'Approved by Financial Institution, VIP',
        '12' => 'Invalid Transaction',
        '13' => 'Invalid Amount',
        '14' => 'Invalid Card Number',
        '15' => 'No Such Financial Institution'
    ];

   public function getCodeMessage($int)
   {

   }
}
