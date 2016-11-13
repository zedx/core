$(document).ready(function() {
  var token = $('meta[name="csrf-token"]').attr('content');

  $('.plugin-switch-status').on('click', function(event, state) {
    var url = $(this).data('url');
    $.ajax({method: "PUT",url: url, data: {_token: token}});
    location.reload();
  });
});
