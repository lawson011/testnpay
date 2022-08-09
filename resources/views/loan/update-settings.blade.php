@extends('layouts.master')

@section('content')
<div class="container-fluid ">
@include('layouts.breadcrumb', ['pageName' => 'Update Loan Setting','page' => 'update loan settings','submenu' => ''])
@include('components._message')
<!-- end page title end breadcrumb -->
<!-- text-sm-center -->
    <div style="margin: 0 auto" class="row w-50 text-center ">
        <div class="col-12 ">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">Update Loan Setting</h5>
                        <form class="form-horizontal" method=POST action ="{{route('admin.loan.settings.update')}}">
                            @method('PUT')
                            @csrf
                            <div class="form-group row">
                                <div class="col-12">
                                <!-- <label for="rate" class="d-block text-left">Rate</label> -->
                                    <input  id="id" type="hidden" class="form-control @error('id') is-invalid @enderror"
                                        name="id" placeholder="id" value="{{encrypt($data['id'])}}" required >

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                <label for="rate" class="d-block text-left">Rate</label>
                                    <input id="rate" type="number" class="form-control @error('rate') is-invalid @enderror"
                                        name="rate" placeholder="Rate" value="{{$data['rate']}}" required >

                                    @error('rate')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                <label for="term" class="d-block text-left">Term</label>

                                    <input id="term" type="number" class="form-control @error('term') is-invalid @enderror"
                                        name="term" placeholder="Term" value="{{$data['term']}}" required >

                                    @error('term')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                <label for="amount" class="d-block text-left">Amount</label>

                                    <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror"
                                        name="amount" placeholder="Amount" value="{{$data['amount']}}"required >

                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="amount" class="d-block text-left">Service charge</label>

                                    <input id="service_charge" type="text" class="form-control @error('service_charge') is-invalid @enderror"
                                           name="service_charge" placeholder="Service Charge" value="{{$data['service_charge']}}"required >

                                    @error('service_charge')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="amount" class="d-block text-left">CBA Loan Product Code</label>

                                    <input id="cba_loan_product_code" type="text" class="form-control @error('cba_loan_product_code') is-invalid @enderror"
                                           name="cba_loan_product_code" placeholder="Amount" value="{{$data['cba_loan_product_code']}}"required >

                                    @error('cba_loan_product_code')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>
                         </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
