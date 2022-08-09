<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\LoanServiceCharge\LoanServiceChargeInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoanServiceChargeController extends Controller
{

    protected $serviceCharge;

    public function __construct(LoanServiceChargeInterface $serviceCharge)
    {
        $this->serviceCharge = $serviceCharge;
    }

    public function index(){
        $data = $this->serviceCharge->getAll()->first();
        return view('loan.service-charge', compact('data'));
    }

    public function update($id,Request $request){
        $request->validate([
            'percentage' => 'required',
        ]);

        $params = $request->all();
        $this->serviceCharge->update($id,$params);

        return redirect()->back()->with('success','Service charge updated successfully');
    }
}
