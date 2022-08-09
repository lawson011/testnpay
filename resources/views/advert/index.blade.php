@extends('layouts.master')

@section('content')
<div class="container-fluid">
@include('layouts.breadcrumb', ['pageName' => 'Advert','page' => 'Advert','submenu' => ''])
<!-- end page title end breadcrumb -->
<div class="row h-100">
    <div class="col-lg-12">
        <div class="card m-b-30">
            <div class="card-body" >
                <div class="row">
                    <div class="col-6">
                        <h4 class="mt-0 header-title">All Adverts</h4>
                    </div>
                    <div class="col-6 text-right" >
                    <a href="{{route('admin.advert.create')}}" class='right btn btn-primary ml-2'>Add New Advert</a>
                    </div>
                </div>
                <br>
                <!-- <p class="text-muted m-b-30 font-14">For basic styling—light padding and only horizontal dividers—add the base class <code>.table</code> to any <code>&lt;table&gt;</code>.</p> -->
                <div id="datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>

                                                <th class="border-top-0">Name</th>
                                                <th class="border-top-0">Image</th>
                                                <th class="border-top-0">Status</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($datas as $data)
                                                <tr>
                                                    <td>{{ $data['name'] }}</td>
                                                    <td>
                                                        <img src="{{ $data['url'] }}" height="150" />
                                                    </td>
                                                    <td>{{ $data['active'] == 1 ? 'Yes' : 'No' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.advert.update',$data['id']) }}"
                                                                class="btn btn-danger">
                                                            Update
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

{{--                                    {{ $html->table( ['width'=>'100%',--}}
{{--                                    'class' => 'table table-bordered table-responsive dataTable no-footer',--}}
{{--                                    'role'=> 'grid',--}}
{{--                                    'aria-describedby' => 'datatable_info'] ) }}--}}

                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </div><!-- end col -->

</div>
@endsection
@section('script')
{{--    {!! $html->scripts() !!}--}}
@endsection

