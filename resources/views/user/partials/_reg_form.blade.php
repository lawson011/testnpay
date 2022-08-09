<div class="modal fade new-user" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">
                    Register New User
                </h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="general-label">
                    <form method="post" action="{{ route('admin.user.create') }}" id="user_form" role="form" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        First Name
                                    </span>
                                </div>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="form-control  @error('first_name') is-invalid @enderror" aria-label="Normal"
                                       aria-describedby="inputGroup-sizing-sm">
                                @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong class="error-message">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        Last Name
                                    </span>
                                </div>
                                <input type="text"  id="last_name" name="last_name" value="{{ old('last_name') }}" class="form-control  @error('last_name') is-invalid @enderror" aria-label="Normal"
                                       aria-describedby="inputGroup-sizing-sm">
                                @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong class="error-message">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        Phone
                                    </span>
                                </div>
                                <input type="number"  id="phone" name="phone"  value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" aria-label="Normal"
                                       aria-describedby="inputGroup-sizing-sm">
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong  class="error-message">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        Email
                                    </span>
                                </div>
                                <input type="email" id="email" name="email"  value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" aria-label="Normal"
                                       aria-describedby="inputGroup-sizing-sm">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong class="error-message">{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        Gender
                                    </span>
                                </div>
                                <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender">
                                    <option value="">Select</option>
                                        <option value="Male" @if(old('gender') == 'Male') selected @endif>
                                         Male
                                        </option>
                                    <option value="Female" @if(old('gender') == 'Female') selected @endif>
                                        Female
                                    </option>
                                </select>
                                @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        Role
                                    </span>
                                </div>
                                {!! Form::select('role[]', allRoles()->pluck('name','id'), old('role'), ['multiple','class' => "select2 mb-3 form-control"]) !!}
                                    @error('role[]')
                                    <span class="invalid-feedback" role="alert">
                                    <strong class="error-message">{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>
                        </div>
                        <input type="hidden" value="{{ old('id') }}" name="id" id="id">
                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        Direct Permissions
                                    </span>
                                </div>
                                {!! Form::select('permissions[]', getAllPermissions()->pluck('name','id'), old('permissions'), ['multiple','class' => "permissionSelect mb-3 form-control"]) !!}
                                @error('permissions')
                                <span class="invalid-feedback" role="alert">
                                    <strong class="error-message">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <button type="submit" class="btn btn-success btn-block waves-effect waves-light">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
