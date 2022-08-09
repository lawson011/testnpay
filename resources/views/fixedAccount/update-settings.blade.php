@extends('layouts.master')

@section('content')
<div class="container-fluid ">
@include('layouts.breadcrumb', ['pageName' => 'Update Fixed Deposit Setting','page' => 'update fixed deposit settings','submenu' => ''])
@include('components._message')
<!-- end page title end breadcrumb -->
<!-- text-sm-center -->
    <div style="margin: 0 auto" class="row w-50 text-center ">
        <div class="col-12 ">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">Update Fixed Setting</h5>
                        <form class="form-horizontal" method=POST action ="{{route('admin.fixed-deposit.settings.store-update')}}">
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
                                <label for="tenure" class="d-block text-left">Tenure(No of days)</label>
                                    <input id="tenure" type="text" class="form-control @error('tenure') is-invalid @enderror"
                                        name="tenure" placeholder="Rate" value="{{$data['tenure']}}" required >

                                    @error('tenure')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                <label for="term" class="d-block text-left">Interest Rate</label>

                                    <input id="interest_rate" type="text" class="form-control @error('interest_rate') is-invalid @enderror"
                                        name="interest_rate" placeholder="Interest Rate" value="{{$data['interest_rate']}}" required >

                                    @error('interest_rate')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                <label for="amount" class="d-block text-left">Product Code</label>

                                    <input id="product_code" type="text" class="form-control @error('product_code') is-invalid @enderror"
                                        name="product_code" placeholder="Product Code" value="{{$data['product_code']}}"required >

                                    @error('product_code')
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
