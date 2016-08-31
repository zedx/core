$(document).ready(function() {

  $("#page-link").change(function() {
    var name = $(this).find(':selected').text();
    $("#page-name, #page-title").val(name);
  });

  $("#page-link").trigger('change');

  $("#link-name").keypress(function() {
    var name = $(this).val();
    $("#link-title").val(name);
  });

  $("#route-name").keypress(function() {
    var name = $(this).val();
    $("#route-title").val(name);
  });

  var capitalizeFirstLetter = function (string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  $('#confirmEditAction').on('show.bs.modal', function (event) {
    var target = $(event.relatedTarget),
      menu = target.data("menu"),
      templateType = 'menu'+capitalizeFirstLetter(menu.type)+'EditTemplate';

    var html = Mustache.to_html($("#" + templateType).html(), menu);
    $("#menuEditContent").html(html);
  });
});
