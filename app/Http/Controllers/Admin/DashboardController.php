<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerCard\CustomerCardInterface;
use Illuminate\Http\Request;

use App\Repositories\Loan\LoanInterface;
use App\Repositories\Auth\AuthInterface;
use Carbon\Carbon;


class DashboardController extends Controller
{
    protected $loan,$customerCard,$auth;

    public function __construct(LoanInterface $loan,CustomerCardInterface $customerCard,AuthInterface $auth){
        $this->loan = $loan;
        $this->customerCard = $customerCard;
        $this->auth = $auth;
    }



    public function index(){

        $data = array(
            'totalLoanApplicant' => $this->loan->getAll()->get()->count(),
            'totalDisbursedLoan' => $this->loan->getAll()->sum('amount'),
            'totalDisbursedLoanCount' => $this->loan->getAll()->count(),
            'totalUnapprovedLoanCount' => $this->loan->findByColumn(['loan_status_id'=>loanStatusByName('Awaiting Approval')->id])->count(),
            'totalDeclinedLoanCount' => $this->loan->findByColumn(['loan_status_id'=>loanStatusByName('Declined')->id])->count(),
            'totalLoanRepaid' => $this->loan->getAll()->sum('repay_amount'),
            'totalLoanRepaidCount'=> $this->loan->getAll()->count(),
            'totalExpectedLoanRepayment' => $this->loan->getAll()->sum('repay_amount'),
            'totalAddCards' => $this->customerCard->getAll()->count(),
            'newLoan' => $this->loan->eagerLoadRelationship(['customer'])->latest()->limit(10)->get(),
            'newLoanApplicant' => $this->loan->getAll()->latest()->limit(10)->get(),
            'totalDueLoan'=>$this->loan->getAll()->sum('repay_amount')
        );
        return view('home.index',compact('data'));
    }

    public function loanStatistics(){
        $days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        $disbursed_loan_days = array();
        $repaid_loan_days = array();
        $params =array('repay'=>'1');
        $disbursed_loan = $this->loan->getBetweenWeek(['disbursed'=>'1'])->groupBy(function($data){
            return Carbon::parse($data["start_date"])->format('D');
        });

        $repaid_loan = $this->loan->getBetweenWeek($params)->groupBy(function($data){
            return Carbon::parse($data["repayment_date"])->format('D');
        });


        foreach($days as $day){
            if(isset($disbursed_loan[$day])){
                $disbursed_loan_days[$day] = $disbursed_loan[$day]->count();
            }else{
                $disbursed_loan_days[$day] = 0;
            }
            if(isset($repaid_loan[$day])){
                $repaid_loan_days[$day] = $repaid_loan[$day]->count();
            }else{
                $repaid_loan_days[$day] = 0;
            }
        }

        $data = array('disbursed_loan_days'=>$disbursed_loan_days,'repaid_loan_days'=>$repaid_loan_days);

        return $data;
    }
}
