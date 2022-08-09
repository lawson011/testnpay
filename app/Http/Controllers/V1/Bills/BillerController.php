<?php


namespace App\Http\Controllers\V1\Bills;


use App\Http\Controllers\Controller;

use App\Services\Etranzact\BillsService;

class BillerController extends Controller
{
    public $account;
    public $billsService;

    public function __construct(BillsService $billsService)
    {
        //$this->billsService = $billsService;
        //$this->account = getUserAccountDetails(auth()->user()->id);
    }

    public function all(){
        //return $this->billsService->send();
    }
}
