@extends('layouts.master')

@section('content')
    <div class="container-fluid">
    @include('layouts.breadcrumb', ['pageName' => 'Customer Profile','page' => 'Customer','submenu' => 'Customer Profile'])
    <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-12">
                @include('components._message')
                @include('components._error')

                <div class="card m-b-30">
                    <div class="card-body">
                        @include('loan.partials._update_status')
                        <div class="row grid-col mb-3">
                            <div class="col-md-12 mx-auto">
                                <div class="row nested-col">
                                    <div class="col-3">
                                        <img width="200" height="240" src="{{ $data->bioData->photo }}"/>
                                        <!-- sync customer account with CBA -->
                                        <p class="text-muted m-t-15 m-b-30 font-14">
                                            <button id='syncCustomerInfo' data-id='{{encrypt($data->id)}}'
                                                    class='btn btn-danger' data-toggle='modal'
                                                    data-animation='bounce'>
                                                Sync Customer Info With CBA
                                            </button>
                                        </p>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <div class="row grid-col">

                                                <div class="col-4">
                                                    <b>Name</b>
                                                </div>
                                                <div class="col-8">
                                                    <p>{{ $data->full_name }}</p>
                                                </div>
                                            </div>
                                            <div class="row grid-col">
                                                <div class="col-4">
                                                    <b>Email</b>
                                                </div>
                                                <div class="col-8">
                                                    <p>{{ $data->email }}</p>
                                                </div>
                                            </div>
                                            <div class="row grid-col">
                                                <div class="col-4">
                                                    <b>Phone</b>
                                                </div>
                                                <div class="col-8">
                                                    <p>{{ $data->phone }}</p>
                                                </div>
                                            </div>

                                            <div class="row grid-col">
                                                <div class="col-4">
                                                    <b>NUBAN</b>
                                                </div>
                                                <div class="col-8">
                                                    <p>{{ $data->nuban }}</p>
                                                </div>
                                            </div>


                                            <div class="row grid-col">
                                                <div class="col-4">
                                                    <b>Username</b>
                                                </div>
                                                <div class="col-8">
                                                    <p>{{ $data->username }}</p>
                                                </div>
                                            </div>

                                            <div class="row grid-col">
                                                <div class="col-4">
                                                    <b>Registration Method</b>
                                                </div>
                                                <div class="col-8">
                                                    <p>{{ $data->registration_method }}</p>
                                                </div>
                                            </div>

                                            <div class="row grid-col">
                                                <div class="col-4">
                                                    <b>Registered Date</b>
                                                </div>
                                                <div class="col-8">
                                                    <p>{{ formatDate($data->created_at)->format('d-m-Y') }}</p>
                                                </div>
                                            </div>

                                            <div class="row grid-col">
                                                <div class="col-4">
                                                    <b>Staff</b>
                                                </div>
                                                <div class="col-8">
                                                    <p>{{ $data->is_staff ? 'Yes' : 'No' }}</p>
                                                </div>
                                            </div>

                                            <div class="row grid-col">
                                                <div class="col-4">
                                                    <p class="text-muted m-b-30 font-14">
                                                        @if($data->blocked == false)
                                                            <button id='blockApplicant' data-id='{{encrypt($data->id)}}'
                                                                    class='btn btn-danger' data-toggle='modal'
                                                                    data-animation='bounce'
                                                                    data-target='.show_block_dropdown'>
                                                                Block
                                                            </button>
                                                        @endif
                                                        @if($data->blocked == true)
                                                            <button id='unblockApplicant'
                                                                    data-id='{{encrypt($data->id)}}'
                                                                    class='btn btn-success' data-toggle='modal'
                                                                    data-animation='bounce'
                                                                    data-target='.show_unblock_dropdown'>
                                                                Un-block
                                                            </button>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="text-muted m-b-30 font-14">
                                                        @if($data->is_staff == false)
                                                            <button id='isStaff' data-id='{{encrypt($data->id)}}'
                                                                    class='btn btn-success' data-toggle='modal'
                                                                    data-animation='bounce'>
                                                                Is Staff
                                                            </button>
                                                        @endif
                                                        @if($data->is_staff == true)
                                                            <button id='isNotAStaff' data-id='{{encrypt($data->id)}}'
                                                                    class='btn btn-danger' data-toggle='modal'
                                                                    data-animation='bounce'>
                                                                Is Not A Staff
                                                            </button>
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="col-4">
                                                    <p class="text-muted m-b-30 font-14">
                                                        @if($data->is_agent == false)
                                                            <button id='isAgent' data-id='{{encrypt($data->id)}}'
                                                                    class='btn btn-success' data-toggle='modal'
                                                                    data-animation='bounce'>
                                                                Is Agent
                                                            </button>
                                                        @endif
                                                        @if($data->is_agent == true)
                                                            <button id='isNotAgent' data-id='{{encrypt($data->id)}}'
                                                                    class='btn btn-danger' data-toggle='modal'
                                                                    data-animation='bounce'>
                                                                Is Not An Agent
                                                            </button>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- block modal -->
                        @include('customer.partials._block_modal')
                        <!-- unblock modal -->
                            @include('customer.partials._unblock_modal')
                        </div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#bioData" role="tab">
                                    Bio-Data
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#nextOfKin" role="tab">
                                    Next of Kin
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#utility" role="tab">
                                    Utility
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#devices" role="tab">
                                    Devices
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#loans" role="tab">
                                    Loans
                                </a>
                            </li>
                        </ul><!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active p-3" id="bioData" role="tabpanel">
                                <p class="font-14 mb-0">

                                    <div class="row grid-col">

                                        <div class="col-2">
                                            <b>BVN</b>
                                        </div>

                                        <div class="col-2">
                                <p>{{ $data->bioData->bvn  ?? ''}}</p>
                            </div>

                            <div class="col-2">
                                <b>BVN Phone</b>
                            </div>

                            <div class="col-2">
                                <p>{{ $data->bioData->bvn_phone  ?? '' }}</p>
                            </div>

                            <div class="col-2">
                                <b>DOB</b>
                            </div>

                            <div class="col-2">
                                <p>{{ $data->bioData->dob }}</p>
                            </div>

                            <div class="col-2">
                                <b>Occupation</b>
                            </div>

                            <div class="col-2">
                                <p>{{ $data->bioData->occupation }}</p>
                            </div>

                            <div class="col-2">
                                <b>Salary Range</b>
                            </div>

                            <div class="col-2">
                                <p>{{ $data->bioData->salary_range }}</p>
                            </div>

                            <div class="col-2">
                                <b>Address</b>
                            </div>

                            <div class="col-2">
                                <p>{{ $data->bioData->address }}</p>
                            </div>

                            <div class="col-2">
                                <b>State</b>
                            </div>

                            <div class="col-2">
                                <p>{{ $data->bioData->state->name ?? ''}}</p>
                            </div>

                            <div class="col-2">
                                <b>City</b>
                            </div>

                            <div class="col-2">
                                <p>{{ $data->bioData->city  ?? ''}}</p>
                            </div>

                            <div class="col-2">
                                <b>Uploaded Passport To CBA</b>
                            </div>

                            <div class="col-2">
                                <p>{{ $data->bioData->upload_photo_to_cba == 1 ? 'Yes' : 'No'}}</p>
                            </div>

                        </div>


                        </p>
                    </div>
                    <div class="tab-pane p-3" id="nextOfKin" role="tabpanel">
                        <p class="font-14 mb-0">
                            <div class="row grid-col">
                                <div class="col-2">
                                    <b>Next of Kin Name</b>
                                </div>

                                <div class="col-2">
                        <p>{{ $data->nextOfKin->name  ?? ''}}</p>
                    </div>

                    <div class="col-2">
                        <b>Next of Kin Phone</b>
                    </div>

                    <div class="col-2">
                        <p>{{ $data->nextOfKin->phone  ?? ''}}</p>
                    </div>

                    <div class="col-2">
                        <b>Next of Kin Address</b>
                    </div>

                    <div class="col-2">
                        <p>{{ $data->nextOfKin->address  ?? ''}}</p>
                    </div>

                    <div class="col-2">
                        <b>Next of Kin State</b>
                    </div>

                    <div class="col-2">
                        <p>{{ $data->nextOfKin->state->name  ?? ''}}</p>
                    </div>

                    <div class="col-2">
                        <b>Next of Kin City</b>
                    </div>

                    <div class="col-2">
                        <p>{{ $data->nextOfKin->city  ?? ''}}</p>
                    </div>

                </div>
                </p>
            </div>
            <div class="tab-pane p-3" id="utility" role="tabpanel">
                <div class="table-responsive b-0" data-pattern="priority-columns">

                    <table id="tech-companies-1" class="table table-striped focus-on">
                        <thead>
                        <tr>
                            <th id="tech-companies-1-col-0" style="display: table-cell;">
                                Type
                            </th>
                            <th data-priority="1" id="tech-companies-1-col-1"
                                style="display: table-cell;">Image
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['identityCard'] as $identity)
                            <tr>
                                <td data-priority="1" width="50%" data-columns="tech-companies-1-col-13"
                                    style="display: table-cell;">
                                    {{ $identity->identityCardType->name }}
                                </td>
                                <td data-priority="2" width="50%" data-columns="tech-companies-1-col-14"
                                    style="display: table-cell;">
                                    <img width="50%" src="{{ $identity->url }}">
                                </td>
                            </tr>
                        @endforeach
                        @foreach($data['utility'] as $Utility)
                            <tr>
                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-1"
                                    style="display: table-cell;">
                                    {{ $Utility->utilityType->name }}
                                </td>
                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-1"
                                    style="display: table-cell;">
                                    <img width="50%" src="{{ $Utility->url }}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane p-3" id="devices" role="tabpanel">

                <div class="table-responsive b-0" data-pattern="priority-columns">

                    <table id="tech-companies-1" class="table table-striped focus-on">
                        <thead>
                        <tr>
                            <th id="tech-companies-1-col-0" style="display: table-cell;">
                                Device Name
                            </th>
                            <th data-priority="3" id="tech-companies-1-col-2">
                                Device ID
                            </th>
                            <th data-priority="1" id="tech-companies-1-col-3"
                                style="display: table-cell;">Active
                            </th>
                            <th data-priority="1" id="tech-companies-1-col-3"
                                style="display: table-cell;">Last Login
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['device'] as $device)
                            <tr>
                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-1"
                                    style="display: table-cell;">
                                    {{ $device->device_name }}
                                </td>
                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-1"
                                    style="display: table-cell;">
                                    {{ $device->device_id }}
                                </td>
                                <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-2">
                                    {{ $device->active == 1 ? 'Yes' : 'No' }}
                                </td>
                                <td style="display: table-cell;">
                                    {{ $device->last_login }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane p-3" id="loans" role="tabpanel">
                <div class="table-responsive b-0" data-pattern="priority-columns">

                    <table id="tech-companies-1" class="table table-striped focus-on">
                        <thead>
                        <tr>
                            <th id="tech-companies-1-col-0" style="display: table-cell;">
                                Amount
                            </th>
                            <th data-priority="3" id="tech-companies-1-col-2">
                                Rate
                            </th>
                            <th data-priority="1" id="tech-companies-1-col-3"
                                style="display: table-cell;">
                                Repayment Amount
                            </th>
                            <th id="tech-companies-1-col-4" style="display: table-cell;">
                                Start Date
                            </th>
                            <th data-priority="3" id="tech-companies-1-col-5">
                                End Date
                            </th>
                            <th id="tech-companies-1-col-7" style="display: table-cell;">
                                Repay
                            </th>
                            {{--                            <th data-priority="3" id="tech-companies-1-col-8">--}}
                            {{--                                Repayment Date--}}
                            {{--                            </th>--}}
                            {{--                            <th data-priority="1" id="tech-companies-1-col-9"--}}
                            {{--                                style="display: table-cell;">--}}
                            {{--                                Repayment Method--}}
                            {{--                            </th>--}}
                            {{--                            <th data-priority="1" id="tech-companies-1-col-9"--}}
                            {{--                                style="display: table-cell;">--}}
                            {{--                                Status--}}
                            {{--                            </th>--}}
                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data['loan'] as $loan)
                            <tr class="@if($loan->loan_status_id == loanStatusByName('Awaiting Approval')->id ||
                                 $loan->loan_status_id == loanStatusByName('Declined')->id)
                                    table-danger @endif">

                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-1"
                                    style="display: table-cell;">
                                    {{ number_format($loan->amount) }}
                                </td>
                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-2"
                                    style="display: table-cell;">
                                    {{ $loan->rate }}
                                </td>
                                <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-3">
                                    {{ number_format($loan->repay_amount) }}
                                </td>
                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-4"
                                    style="display: table-cell;">
                                    {{ formatDate($loan->start_date ?? $loan->created_at)->format('d-m-Y') }}
                                </td>
                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-5"
                                    style="display: table-cell;">
                                    {{ formatDate($loan->end_date)->format('d-m-Y') }}
                                </td>
                                {{--                                <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-6">--}}
                                {{--                                    {{ $loan->disbursed == 1 ? 'Yes' : 'No' }}--}}
                                {{--                                </td>--}}
                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-7"
                                    style="display: table-cell;">
                                    {{ $loan->repay == 1 ? 'Yes' : 'No' }}
                                </td>
                                {{--                                <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-8"--}}
                                {{--                                    style="display: table-cell;">--}}
                                {{--                                    {{ $loan->repayment_date ? formatDate($loan->repayment_date)->format('d-m-Y')--}}
                                {{--                                    : ''--}}
                                {{--                                    }}--}}
                                {{--                                </td>--}}
                                {{--                                <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-9">--}}
                                {{--                                    {{ $loan->repaymentMethod['method'] ?? ''}}--}}
                                {{--                                </td>--}}
                                <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-9">
                                    {{ $loan->status->name }}
                                </td>
                                @if($loan->loan_status_id == loanStatusByName('Awaiting Approval')->id ||
                                 $loan->loan_status_id == loanStatusByName('Declined')->id)
                                    <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-9">
                                        <button id="updateStatus" data-id='{{encrypt($loan->id)}}' data-toggle="modal"
                                                data-animation="bounce"
                                                data-target=".update-status"
                                                class="btn-success">Update Status
                                        </button>
                                    </td>

                            @endif
                            <!--  Modal for update status -->
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script')
    @include('loan.partials.script')
    @include('customer.partials.script')
@endsection
