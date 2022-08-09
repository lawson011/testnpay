@extends('layouts.master')

@section('content')
<div class="container-fluid ">
@include('layouts.breadcrumb', ['pageName' => 'Add Loan Setting','page' => 'add loan settings','submenu' => ''])
@include('components._message')
<!-- end page title end breadcrumb -->
<!-- text-sm-center -->
    <div style="margin: 0 auto" class="row w-50 text-center ">
        <div class="col-12 ">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">Add Loan Setting</h5>
                        <form class="form-horizontal" method=POST action ="{{route('admin.loan.settings.add')}}">
                            @csrf
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="rate" type="number" class="form-control @error('rate') is-invalid @enderror"
                                        name="rate" placeholder="Rate" required >

                                    @error('rate')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="term" type="number" class="form-control @error('term') is-invalid @enderror"
                                        name="term" placeholder="Term" required >

                                    @error('term')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror"
                                        name="amount" placeholder="Amount" required >

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
