@extends('layouts.master')

@section('content')
    <div class="container-fluid">
    @include('layouts.breadcrumb', ['pageName' => 'Role Management','page' => 'Settings','submenu' => 'Role Management'])
    <!-- end page title end breadcrumb -->
        <div class="row">
            <div class="col-lg-12">
                @include('components._message')
                @include('components._error')
            </div>
            @foreach($roles as $role)
                <div class="col-lg-12">
                    <div class="card m-b-30">
                        <h4 class="card-header mt-0">
                            {{ ucfirst($role->name).' ' }} Permissions
                        </h4>
                        <div class="card-body">
                            <div class="row grid-col">
                                <form class="form-inline" method="post"
                                      action="{{ route('admin.role.update', encrypt($role->id))}}) }}">
                                    @csrf

                                    @foreach($permissions as $perm)
                                        @php
                                            $per_found = null;

                                            if( isset($role) ) {
                                                $per_found = $role->hasPermissionTo($perm->name);
                                            }

                                        @endphp
                                        <div class="col-sm-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="{{$perm->id}}" name="permissions[]"
                                                       @if($perm->name == $per_found) checked @endif
                                                       class="custom-control-input"
                                                       id="{{$role->name}}{{$perm->id + $role->id}}"
                                                       data-parsley-multiple="groups" data-parsley-mincheck="2">
                                                <label
                                                    class="custom-control-label {{ Illuminate\Support\Str::contains($perm->name, 'delete') ? 'text-danger' : '' }}"
                                                    for="{{$role->name}}{{$perm->id + $role->id}}">
                                                    {{ ucwords($perm->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="mt-5 col-sm-12">
                                        <button type="submit" class="btn btn-primary">
                                            Update
                                        </button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection

@section('script')

@endsection
