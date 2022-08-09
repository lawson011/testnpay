<div class="modal fade edit-loan-service-charge" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">
                    Edit Loan Service Charge
                </h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">
                <div class="general-label">
                    <form method="post" action="{{ route('admin.loan.update-service-charge',$data['id']) }}" role="form" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        Percentage
                                    </span>
                                </div>

                                <input type="text" name="percentage" value="{{ $data['percentage'] }}" class="form-control
                                @error('percentage') is-invalid @enderror" aria-label="Normal"
                                       aria-describedby="inputGroup-sizing-sm">
                                @error('percentage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <button type="submit" class="btn btn-success btn-block waves-effect waves-light">
                                    {{ __('Update') }}
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
