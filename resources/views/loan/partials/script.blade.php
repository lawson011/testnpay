<script>

    $(document).on("click", "#updateStatus", function (e) {
      $('#loanID').val($(this).data('id'));
    });

    @if($errors->any())
    $('.update-status').modal('show');
    @endif

</script>

