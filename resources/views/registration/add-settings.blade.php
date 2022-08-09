@extends('layouts.master')

@section('content')
<div class="container-fluid ">
@include('layouts.breadcrumb', ['pageName' => 'New Registration Setting','page' => 'registration settings','submenu' => ''])
@include('components._message')
<!-- end page title end breadcrumb -->
<!-- text-sm-center -->
    <div style="margin: 0 auto" class="row w-50 text-center ">
        <div class="col-12 ">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">New Registration Setting</h5>
                        <form class="form-horizontal" method=POST action ="{{route('admin.registration.settings-new')}}">
                            @csrf
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="product_code" value="{{ old('product_code') }}" type="text" class="form-control @error('product_code') is-invalid @enderror"
                                        name="product_code" placeholder="Product Code" required >

                                    @error('product_code')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="account_officer_code" value="{{ old('account_officer_code') }}" type="text" class="form-control @error('account_officer_code') is-invalid @enderror"
                                        name="account_officer_code" placeholder="Account Officer Code" required >

                                    @error('account_officer_code')
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
