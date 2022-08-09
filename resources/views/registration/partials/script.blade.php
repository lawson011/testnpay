<script>

    $(document).on("click", "#updateStatus", function (e) {
      $('#loanID').val($(this).data('id'));
    });

    @if($errors->any())
    $('.update-status').modal('show');
    @endif

        $(document).on('click','#makeActive',  function (e) {

        const id = $(this).data('id')
        alertify.confirm("Are you sure you want to make this product active?", function (e) {

            const url = `{{ route('admin.registration.settings-active') }}`
            $.ajax({
                url: url,
                type: "get",
                data: {'id': id},
                success: function (res) {
                    alertify.success("Successful")
                    window.location.reload();
                },
                error: function(res){
                    alertify.error(res.responseJSON.message);
                    window.location.reload();
                }
            });
        }, function (e) {

        });
    });


</script>

