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

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('zedx-setting-active-tab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('zedx-setting-active-tab');
    if(activeTab){
        $('#setting-tab a[href="' + activeTab + '"]').tab('show');
    }
});
