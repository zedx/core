$(document).ready(function() {
  $('.theme-switch').on('ifChecked', function(event){
    var theme = this.value,
      url = $('#themesTable').data('seturl');
      token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        method: "PUT",
        url: url,
        dataType: 'JSON',
        data: {
            _token: token,
            theme: theme,
        }
    });
  });

  $('#recompileThemeTemplates').on('click', function() {
    var url = $(this).data('url');
      token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        method: "POST",
        url: url,
        dataType: 'JSON',
        data: {
            _token: token
        }
    });
  })
});
