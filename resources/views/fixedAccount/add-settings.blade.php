@extends('layouts.master')

@section('content')
<div class="container-fluid ">
@include('layouts.breadcrumb', ['pageName' => 'Add Fixed Deposit Setting','page' => 'add fixed deposit settings','submenu' => ''])
@include('components._message')
<!-- end page title end breadcrumb -->
<!-- text-sm-center -->
    <div style="margin: 0 auto" class="row w-50 text-center ">
        <div class="col-12 ">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">Add New Fixed Deposit(Investment) Setting</h5>
                        <form class="form-horizontal" method=POST action ="{{route('admin.fixed-deposit.store-new-setting')}}">
                            @csrf
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="tenure" type="number" class="form-control @error('tenure') is-invalid @enderror"
                                        name="tenure" placeholder="Tenure(No of days)" required >

                                    @error('tenure')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="interest_rate" type="text" class="form-control @error('interest_rate') is-invalid @enderror"
                                        name="interest_rate" placeholder="Interest Rate" required >

                                    @error('interest_rate')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="product_code" type="text" class="form-control @error('product_code') is-invalid @enderror"
                                        name="product_code" placeholder="Product Code" required >

                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                         </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
