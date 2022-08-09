<?php

namespace App\Services\Paystack;

class PaystackConstant{

    const BASE_URL = "https://api.paystack.co"; // base url
    const TRANS_BASE_URL = self::BASE_URL."/transaction"; // transaction base url
    const VERIFY_TRANS_URL = self::TRANS_BASE_URL."/verify/";// verify payment url

    const CHARGE_CARD_URL = self::TRANS_BASE_URL."/charge_authorization";

    const RESOLVE_BVN  = self::BASE_URL.'/bank/resolve_bvn/';

    // REFUND
    const REFUND_BASE_URL  = self::BASE_URL."/refund";
    const CREATE_RECIPIENT = self::BASE_URL."/transferrecipient";
    const PAYSTACK_INITIATE_TRANSFER = self::BASE_URL."/transfer";

    const BANK_BASE_URL = self::BASE_URL."/bank";
    const RESOLVE_ACCOUNT_NUMBER_URL = self::BANK_BASE_URL."/resolve";
    const MINIMUM_BALANCE = self::BASE_URL."/balance";

    public function getSecretKey(){
        return env("PAYSTACK_KEY");
    }

}
