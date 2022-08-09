<div class="modal fade update-status" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">
                    Update Loan Status
                </h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×
                </button>
            </div>
            <div class="modal-body">

                <div class="general-label">
                    <form method="post" action="{{ route('admin.loan.update-status',$id ?? '') }}" role="form" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-10 input-group mt-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroup-sizing-normal">
                                        Status
                                    </span>
                                </div>

                                <input id="loanID" type="hidden" name="loan_id"  value="{{ old('loan_id') }}" class="form-control" aria-label="Normal"
                                       aria-describedby="inputGroup-sizing-sm">

                                <select class="form-control @error('loan_status') is-invalid @enderror" name="loan_status">
                                    <option value="">Select</option>
                                    @foreach(getLoanStatus('Awaiting Approval') as $status)
                                    <option value="{{ $status->id }}" @if(old('loan_status') == $status->id) selected @endif>
                                        {{ $status->name }}
                                    </option>
                                    @endforeach
                                </select>
                                    @error('loan_status')
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
                                        Remarks
                                    </span>
                                </div>
                                <textarea name="remarks"  class="form-control @error('remarks') is-invalid @enderror" aria-label="Normal"
                                          aria-describedby="inputGroup-sizing-sm">{{ old('remarks') }}</textarea>

                                @error('remarks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
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
