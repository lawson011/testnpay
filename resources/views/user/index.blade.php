@extends('layouts.master')

@section('content')
    <div class="container-fluid">
    @include('layouts.breadcrumb', ['pageName' => 'User Management','page' => 'Settings','submenu' => 'User Management'])
    <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-12">
                @include('components._message')
                @include('components._error')
                <div class="card m-b-30">
                    <div class="card-body">
                        <button type="button" class="m-b-20 btn btn-primary waves-effect waves-light"
                                data-toggle="modal" data-animation="bounce"
                                data-target=".new-user">
                            New User
                        </button>

                        <!--  Modal for new user -->
                        @include('user.partials._reg_form')

                        <div id="datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                            <div class="row">
                                <div class="col-sm-12 table-responsive">

                                    {{ $html->table( ['width'=>'100%',
                                    'class' => 'table table-bordered dataTable no-footer',
                                    'role'=> 'grid',
                                    'aria-describedby' => 'datatable_info'] ) }}

                                </div>
                            </div>
                        </div>

                        <!-- block modal -->
                        @include('user.partials._block_modal')
                        <!-- unblock modal -->
                        @include('user.partials._unblock_modal')


                    </div>
                </div>
            </div><!-- end col --></div>

    </div>
@endsection

@section('script')
    {!! $html->scripts() !!}
  @include('user.partials.script')
@endsection
