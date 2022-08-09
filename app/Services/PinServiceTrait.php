<?php


namespace App\Services;


trait PinServiceTrait
{
    public function pinNotSet($customer)
    {
        return is_null($customer->transaction_pin) ? true : false;
    }

}
