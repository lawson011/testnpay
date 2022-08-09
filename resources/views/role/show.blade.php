@extends('layouts.master')

@section('content')
    <div class="container-fluid">
    @include('layouts.breadcrumb', ['pageName' => 'Role Management',
'page' => 'Settings','submenu' => 'New Role'])
    <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-lg-12">
                @include('components._message')
                @include('components._error')
            </div>

                <div class="col-lg-12">
                    <div class="card m-b-30">
                        <h4 class="card-header mt-0">
                             New Role
                        </h4>

                        <div class="card-body">

                            <div class="row grid-col">
                                <form class="form-inline" method="post"
                                      action="{{ route('admin.role.create') }}">
                                    @csrf
                                    <div class="col-lg-12 mb-3">
                                        <div class="custom-control">
                                            <input placeholder="Role" value="{{ old('role') }}" name="role" class="form-control">
                                            @error('role')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="error-message">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    @foreach($permissions as $perm)
                                        <div class="col-sm-3">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" name="permissions[]" type="checkbox" value="{{ $perm->id }}" id="{{ $perm->id }}">
                                                <label class="form-check-label {{ Illuminate\Support\Str::contains($perm->name, 'delete') ? 'text-danger' : '' }}"
                                                       for="defaultCheck1">
                                                    {{ ucwords($perm->name) }}
                                                </label>
                                            </div>
                                            @error('permissions')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="error-message">
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                            @enderror
                                        </div>
                                    @endforeach
                                    <div class="mt-5 col-sm-12">
                                        <button type="submit" class="btn btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
