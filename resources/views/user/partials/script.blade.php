<script>
    //block data
    var block_options = ["phishing","sacked","retired"];
    var block_data = "";
    var block_message="";
    var block_id = "";
    var block_url = "{{ route('admin.user.block') }}";
    block_options.forEach(element=>block_data+='<option class="dropdown-item reason_item" href="#" value="'+element+'">'+element+'</option>');
    block_data+='<option class="dropdown-item reason_item" href="#" data-dismiss="modal" aria-hidden="true">Other</option>';
    $("#block_reasons").append(block_data);

    $(".select2").select2({
        width: "85%"
    });

    $(".permissionSelect").select2({
        width: "57%"
    });

    $('.new-user').on('hidden.bs.modal', function(e) {
        $('#myLargeModalLabel').html('New Admin');
        $(this).find('#user_form')[0].reset();
        $('.select2').val([]).trigger('change');
        $('#email').val('').prop('readonly',false).trigger('change');
        $('.permissionSelect').val([]).trigger('change');
        $('.form-control').removeClass('is-invalid');
        $('[class^=error-message]').html('');

    });

    $(document).on("click","#editAdmin",function(e){

        $('.form-control').removeClass('is-invalid');
        $('[class^=error-message]').html('');

        $('#myLargeModalLabel').html('Edit Admin');

       const details = $(this).data('details');

        var url = '{{ route("admin.user.edit", ":id") }}';

        url = url.replace(':id',details.id);

        $('#user_form').attr('action', url);

        $('#first_name').val(details.first_name);
        $('#last_name').val(details.last_name);
        $('#phone').val(details.phone);
        $('#gender').val(details.gender);
        $('#email').val(details.email).prop('readonly',true);

        $('#id').val(details.id);

        let selectUserRoles = details.roles.map(obj => {
            return obj.id
        });

        let selectUserDirectPermissions = details.permissions.map(obj => {
            return obj.id
        });

        $('.select2').val(selectUserRoles).trigger('change');

        $('.permissionSelect').val(selectUserDirectPermissions).trigger('change');

        $('.new-user').modal('show');

    });

// #blockAdmin
    $(document).on("click","#blockAdmin",function(e){
        block_id = $(this).data('id');
    });

    $(document).on("change","#block_reasons",function(e){
        block_message = $(this).val();
    });

    $(document).on("click","#block",function(e){
        if(block_message === "Other"){
            alertify.prompt("Reason for blocking ?", function (e) {
                block_message = e;
                alertifyconfirm(block_id,block_message,block_url,"block");
            }
            );
        }else if(block_options.indexOf(block_message)>-1){
            alertifyconfirm(block_id,block_message,block_url,"block");

        }
    });

    //unblock data
    var unblock_options = ["not phishing","not error1","not error2"];
    var unblock_data = "";
    var unblock_message="";
    var unblock_id = "";
    var unblock_url = "{{ route('admin.user.unblock') }}";
    unblock_options.forEach(element=>unblock_data+='<option class="dropdown-item reason_item" href="#" value="'+element+'">'+element+'</option>');
    unblock_data+='<option class="dropdown-item reason_item" href="#" data-dismiss="modal" aria-hidden="true">Other</option>';
    $("#unblock_reasons").append(unblock_data);

// #blockAdmin

    $(document).on("click","#unblockAdmin",function(e){
        unblock_id = $(this).data('id');
    });

    $(document).on("change","#unblock_reasons",function(e){
        unblock_message = $(this).val();
    });

    $(document).on("click","#unblock",function(e){
        if(unblock_message == "Other"){
            alertify.prompt("Reason for unblocking ?", function (e) {
                unblock_message = e;
                alertifyconfirm(unblock_id,unblock_message,unblock_url,"unblock");
            }
            );
        }else if(unblock_options.indexOf(unblock_message)>-1){
            alertifyconfirm(unblock_id,unblock_message,unblock_url,"unblock");
        }
        });

const alertifyconfirm = (id,message,url,text)=>{
    alertify.confirm("Are you sure you want to "+text+" this user?",function(e){
                    $.ajax({
                    url: url,
                    type: "put",
                    data: {'id': id,'reason':message},
                    success: function (res) {
                        alertify.success("Successful")
                        window.location.reload();
                    },
                    error: function(res){
                        alertify.error(res.responseJSON.message);
                        window.location.reload();
                    }
                });
                },function(e){

                });
}

    @if($errors->any())
    $('#myLargeModalLabel').html('Edit Admin');

    const id = `{{ old('id') }}`;
    const email = `{{ old('email') }}`;

    var url = '{{ route("admin.user.edit", ":id") }}';

    url = url.replace(':id', id);

    $('#user_form').attr('action', url);
    $('#email').val(email).prop('readonly',true);
    $('.new-user').modal('show');
    @endif
</script>
