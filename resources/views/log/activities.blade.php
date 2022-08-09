@extends('layouts.master')

@section('content')
<div class="container-fluid">
@include('layouts.breadcrumb', ['pageName' => 'Log Activities','page' => 'log activities','submenu' => ''])
<!-- end page title end breadcrumb -->
<div class="row h-100">
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-body" >
                <h4 class="mt-0 header-title">All log activities</h4>
                <!-- <p class="text-muted m-b-30 font-14">For basic styling—light padding and only horizontal dividers—add the base class <code>.table</code> to any <code>&lt;table&gt;</code>.</p> -->
                <div id="datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                            <div class="row">
                                <div class="col-sm-12">

                                    {{ $html->table( ['width'=>'100%',
                                    'class' => 'table table-bordered table-responsive dataTable no-footer',
                                    'role'=> 'grid',
                                    'aria-describedby' => 'datatable_info'] ) }}

                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div><!-- end col -->

</div>
@endsection
@section('script')
    {!! $html->scripts() !!}
@endsection

