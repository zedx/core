$(document).ready(function() {
    $('.provider-client').editable({
        success: function(response, newValue) {
            var providerName = $(this).closest('tr').data('provider');
            $('#client_id_' + providerName).val(newValue);
        }
    });

    $('.provider-secret').editable({
        success: function(response, newValue) {
            var providerName = $(this).closest('tr').data('provider');
            $('#secret_key_' + providerName).val(newValue);
        }
    });
});
