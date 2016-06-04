$(document).ready(function() {
    var token = $('meta[name="csrf-token"]').attr('content');
    $('.gateway-switch-status').on('switchChange.bootstrapSwitch', function(event, state) {
        var url = $(this).data('url');
        $.ajax({
            method: "PUT",
            url: url,
            data: {
                _token: token
            }
        });
    });

    $('#gateway-currency').on('change', function() {
        var url = $(this).data('url'),
            currency = this.value;

        $.ajax({
            method: "PUT",
            url: url,
            data: {
                _token: token,
                currency: currency
            }
        });
    });
});
