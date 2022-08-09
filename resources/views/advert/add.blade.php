@extends('layouts.master')

@section('content')
<div class="container-fluid ">
@include('layouts.breadcrumb', ['pageName' => 'Add Advert','page' => 'add advert','submenu' => ''])
@include('components._message')
<!-- end page title end breadcrumb -->
<!-- text-sm-center -->
    <div style="margin: 0 auto" class="row w-50 text-center ">
        <div class="col-12 ">
            <div class="card bg-white m-b-30">
                <div class="card-body new-user">
                    <h5 class="header-title mb-4 mt-0">Add New Advert</h5>
                        <form enctype="multipart/form-data" class="form-horizontal" method=POST action ="{{route('admin.advert.store')}}">
                            @csrf
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" placeholder="Name" required >

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <input id="image" type="file" class="form-control @error('image') is-invalid @enderror"
                                        name="image" placeholder="Image" required >

                                    @error('image')
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
