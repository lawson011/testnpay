@extends('layouts.master')
@section('content')
<div class="container-fluid">
@include('layouts.breadcrumb', ['pageName' => 'Dashboard','page' => 'dashboard','submenu' => ''])
<!-- end page title end breadcrumb -->
    <div class="row"><!-- Column -->
        <div class="col-md-4 col-lg-4 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 align-self-center text-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">{{ $data['totalLoanApplicant'] }}</h5>
                                <p class="mb-4 text-muted">Total Loan Applicant</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">&#8358;{{ number_format($data['totalDisbursedLoan']) }}</h5>
                                <p class="mb-4 text-muted">Total Disbursed Loan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">&#8358;{{ number_format($data['totalLoanRepaid']) }}</h5>
                                <p class="mb-4 text-muted">Total Repaid Loan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">&#8358;{{ number_format($data['totalExpectedLoanRepayment'])}}</h5>
                                <p class="mb-0 text-muted">Total Expected Loan Repayment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">&#8358;{{ number_format($data['totalDueLoan'])}}</h5>
                                <p class="mb-0 text-muted">Total Due Loan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">{{ $data['totalAddCards'] }}</h5>
                                <p class="mb-4 text-muted">Total Add Cards</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">{{ $data['totalDisbursedLoanCount'] }}</h5>
                                <p class="mb-4 text-muted">Total Loan Disbursed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">{{ $data['totalUnapprovedLoanCount'] }}</h5>
                                <p class="mb-4 text-muted">Total Loan Awaiting Approval</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">{{ $data['totalDeclinedLoanCount'] }}</h5>
                                <p class="mb-4 text-muted">Total Loan Declined</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Column --><!-- Column -->
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="d-flex flex-row">
                        <div class="col-10 text-center align-self-center">
                            <div class="m-l-10">
                                <h5 class="mt-0 round-inner">{{ $data['totalLoanRepaidCount'] }}</h5>
                                <p class="mb-4 text-muted">Total Loan Repaid</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row" >
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">
                   <h4 class="mt-0 header-title">Loan Statistics</h4>
                   <!-- <p class="text-muted m-b-30 font-14 d-inline-block text-truncate w-100">Loan Statistics</p> -->
                   <div id="line-chart" class="ct-chart ct-golden-section">

                   </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-8">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">New Loan</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>

                                <th class="border-top-0">Name</th>
                                <th class="border-top-0">Amount</th>
                                <th class="border-top-0">Repayment Amount</th>
                                <th class="border-top-0">Start Date</th>
                                <th class="border-top-0">End Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($data['newLoan'] as $loan)
                            <tr>
                                <td>
                                    <a href="javascript:void(0);">{{$loan->customer->full_name}}</a></td>
                                <td>&#8358;{{number_format($loan->amount)}}</td>
                                <td>&#8358;{{number_format($loan->repay_amount)}}</td>
                                <td>{{formatDate($loan->start_date)->format('d-m-Y')}}</td>
                                <td>{{formatDate($loan->end_date)->format('d-m-Y')}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-12 col-xl-4">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mt-0 mb-4">
                        New Loan Applicant
                    </h5>
                    <ul class="list-unstyled mb-0 pr-3" id="boxscroll2" tabindex="1"
                        style="overflow: hidden; outline: none;">
                        @foreach ($data['newLoanApplicant'] as $loanapplicant)
                        <li class="p-3">
                            <div class="media">
                                <div class="thumb float-left">
                                    <a href="#">
                                        <img class="rounded-circle" src="{{$loanapplicant->customer->bioData->first()->photo ?? ''}}" alt="">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <p class="media-heading mb-0">
                                        {{$loanapplicant->first_name}} {{$loanapplicant->last_name}}
                                        <i class="fa fa-circle text-success mr-1 pull-right"></i>
                                    </p>

                                    <small class="text-muted">
                                        {{$loanapplicant->customer->bioData()->first()->state->name ?? ''}}
                                    </small>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
  @include('home.partials.script')
@endsection
