$(function() {
    $notificationItems = $('#notificationItems');
    var loading_options = {
        finishedMsg: "",
        msgText: "<div class='center'>Loading news items...</div>",
        img: $notificationItems.data('ajax-loader')
    };
    $notificationItems.infinitescroll({
        loading: loading_options,
        navSelector: "#notifications .pagination",
        nextSelector: "#notifications .pagination li.active + li a",
        itemSelector: "#notificationItems li.item"
    });
    var $notificationDaterange = $('.notificationDaterange'),
        url = $notificationDaterange.data('url');
    $notificationDaterange.daterangepicker({
        showDropdowns: true
    }, function(start, end, label) {
        window.location.href = url + '?dateFrom=' + start.format('MM/DD/YYYY') + '&dateTo=' + end.format('MM/DD/YYYY');
    });
    // Mark all as read
    $('#mark-all-notifications').on('click', function() {
        var url = $(this).data('url'),
            token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            method: "PUT",
            url: url,
            dataType: 'JSON',
            data: {
                _token: token,
            }
        }).done(function(response) {
            if (!response.success) {
                return;
            }
            $('#notifications-number').remove();
            $("#notifications-menu li").removeClass("new");
        });
    });
});
