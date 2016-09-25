$(document).ready(function() {
  $('.theme-switch').on('click', function(event){
    var theme = $(this).data('name'),
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
    }).always(function() {
      location.reload();
    });
  });

  $('#recompileThemeTemplates').on('click', function() {
    $('.recompile-icon').addClass('fa-spin');

    var $this = $(this);
    var url = $this.data('url');
      token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        method: "POST",
        url: url,
        dataType: 'JSON',
        data: {
            _token: token
        }
    }).always(function() {
      $this.prop("disabled", true);
      $('.recompile-icon').removeClass().addClass("fa fa-check");
    });
  })
});
