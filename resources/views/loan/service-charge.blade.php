@extends('layouts.master')

@section('content')
    <div class="container-fluid">
    @include('layouts.breadcrumb', ['pageName' => 'Loan Service Charge','page' => 'Settings','submenu' => 'Loan Service Charge'])
    <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-12">
                @include('components._message')
                @include('components._error')
                <div class="card m-b-30">
                    <div class="card-body">

                        <!--  Modal for new user -->
                        @include('loan.partials._edit_loan_service_charge')

                        <div id="datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="table-responsive">
                                        <table class="table table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                            <thead>
                                            <tr>

                                                <th>Percentage</th>
                                                <th>Admin</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                                <tr>
                                                    <td>{{ $data['percentage'] }}</td>
                                                    <td>{{ $data->user->full_name }}</td>
                                                    <td>
                                                        <button type="button" class="m-b-20 btn btn-primary waves-effect waves-light"
                                                                data-toggle="modal" data-animation="bounce"
                                                                data-target=".edit-loan-service-charge"
                                                                data-id="{{ $data['id'] }}"
                                                                data-percentage="{{ $data['percentage'] }}">
                                                            Edit
                                                        </button>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div><!-- end col --></div>"

    </div>
@endsection

@section('script')

    <script>
@if($errors->any())
    $(".edit-loan-service-charge").modal("show");
@endif
    </script>
@endsection
