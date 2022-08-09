@extends('layouts.master')

@section('content')
<div class="container-fluid ">
@include('layouts.breadcrumb', ['pageName' => 'Profile','page' => 'profile','submenu' => ''])
<!-- end page title end breadcrumb -->
<!-- text-sm-center -->
    <div class="row w-100 text-md-start ">
        <div class="col-12 ">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">User Profile</h5>
                    <div class="row h-100" >
                        <div class="col-12 col-md-2 pb-xs-4 pb-md-0">
                            @if($data['user']->bioData->first() && $data['user']->bioData->first()->photo)
                                <img  alt="no_picture" src="{{$data['user']->bioData->first()->photo}}" class="img-fluid" alt="Responsive image">
                                @else
                                <img  alt="no_picture" src="{{asset('assets/images/users/avatar-1.jpg')}}" class="img-fluid" alt="Responsive image">
                            @endif
                        </div>
                        <div class="col-12 col-md-10 align-self-center " >
                            <div class="row">
                            <div class="col-12 col-lg-9" >
                                <div class="d-flex">
                                    <div class="col">
                                        <h6 class="">{{$data['user']->first_name}} {{$data['user']->last_name}}</h6>
                                    </div>

                                </div>
                                <div class="d-flex">
                                    <div class="col">
                                    <span style="margin: 10px 0" class="d-block">{{$data['user']->email}}</span>
                                    </div>
                                    <div class="col">
                                        <span style="margin: 10px 0" class="d-block">{{$data['user']->gender}}</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="col">
                                        <span style="margin: 10px 0" class="d-block">{{$data['user']->phone}}</span>
                                    </div>
                                    <div class="col">
                                        <span style="margin: 10px 0" class="d-block text-primary">Administrator</span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-lg-3 align-self-center">
                            <a href="{{route('admin.changePassword')}}" type="button" class="btn btn-primary waves-effect waves-light">Change Password</a>
                            </div>

                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
