@extends('layouts.master')

@section('content')
    <div class="container-fluid">
    @include('layouts.breadcrumb', ['pageName' => 'Approved Loans','page' => 'Loan','submenu' => 'Approved Loans'])
    <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-12">
                @include('components._message')
                @include('components._error')
                <div class="card m-b-30">
                    <div class="card-body">

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
            </div><!-- end col --></div>

    </div>
@endsection

@section('script')
    {!! $html->scripts() !!}
@endsection
