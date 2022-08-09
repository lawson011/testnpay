<script>

    //block data
    var block_options = ["phishing", "in-active"];
    var block_data = "";
    var block_message = "";
    var block_id = "";
    var block_url = "{{ route('admin.customer.block') }}";
    block_options.forEach(element => block_data += '<option class="dropdown-item reason_item" href="#" value="' + element + '">' + element + '</option>');
    block_data += '<option class="dropdown-item reason_item" href="#" data-dismiss="modal" aria-hidden="true">Other</option>';
    $("#block_reasons").append(block_data);

    // #blockAdmin

    $(document).on("click", "#blockApplicant", function (e) {

        block_id = $(this).data('id');

    });

    $(document).on("change", "#block_reasons", function (e) {

        block_message = $(this).val();

    });

    $(document).on("click", "#block", function (e) {

        if (block_message == "Other") {
            alertify.prompt("Reason for blocking ?", function (e) {
                    block_message = e;
                    alertifyconfirm(block_id, block_message, block_url, "block");
                }
            );
        } else if (block_options.indexOf(block_message) > -1) {
            alertifyconfirm(block_id, block_message, block_url, "block");

        }
    });

    //unblock data
    var unblock_options = ["not phishing", "active"];
    var unblock_data = "";
    var unblock_message = "";
    var unblock_id = "";
    var unblock_url = "{{ route('admin.customer.unblock') }}";
    unblock_options.forEach(element => unblock_data += '<option class="dropdown-item reason_item" href="#" value="' + element + '">' + element + '</option>');
    unblock_data += '<option class="dropdown-item reason_item" href="#" data-dismiss="modal" aria-hidden="true">Other</option>';
    $("#unblock_reasons").append(unblock_data);

    // #blockAdmin

    $(document).on("click", "#unblockApplicant", function (e) {

        unblock_id = $(this).data('id');

    });

    $(document).on("change", "#unblock_reasons", function (e) {

        unblock_message = $(this).val();

    });

    $(document).on("click", "#unblock", function (e) {

        if (unblock_message === "Other") {
            alertify.prompt("Reason for unblocking ?", function (e) {
                    unblock_message = e;
                    alertifyconfirm(unblock_id, unblock_message, unblock_url, "unblock");
                }
            );
        } else if (unblock_options.indexOf(unblock_message) > -1) {
            alertifyconfirm(unblock_id, unblock_message, unblock_url, "unblock");

        }
    });

    const alertifyconfirm = (id, message, url, text) => {
        alertify.confirm( text, function (e) {
            $.ajax({
                url: url,
                type: "put",
                data: {'id': id, 'reason': message},
                success: function (res) {
                    console.log(res)
                    alertify.success("Successful")
                    window.location.reload();
                },
                error: function (res) {
                    alertify.error(res.responseJSON.message);
                    // window.location.reload();
                }
            });
        }, function (e) {

        });
    }

    $(document).on("click", "#isStaff", function (e) {
        const url = `{{ route('admin.customer.is_staff') }}`;
        alertifyconfirm($(this).data('id'), '',url,'Are you sure you want to Staff this customer?')
    });

    $(document).on("click", "#isNotAStaff", function (e) {
        const url = `{{ route('admin.customer.is_not_staff') }}`;
        alertifyconfirm($(this).data('id'), '',url,'Are you sure you want to un staff this customer?')
    });

    $(document).on("click", "#isAgent", function (e) {
        const url = `{{ route('admin.customer.is_agent') }}`;
        alertifyconfirm($(this).data('id'), '',url,'Are you sure you want to make this customer an AGENT?')
    });

    $(document).on("click", "#isNotAgent", function (e) {
        const url = `{{ route('admin.customer.is_not_agent') }}`;
        alertifyconfirm($(this).data('id'), '',url,'Are you sure this customer is not an AGENT?')
    });

    $(document).on("click", "#syncCustomerInfo", function (e) {
        const url = `{{ route('admin.customer.info-sync') }}`;
        alertifyconfirm($(this).data('id'), '',url,'Are you sure you want to sync customer info, this will override info in CBA?')
    });

</script>
