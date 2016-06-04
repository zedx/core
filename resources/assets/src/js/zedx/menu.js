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

  $('#confirmEditAction').on('show.bs.modal', function (event) {
    var target = $(event.relatedTarget),
      menu = target.data("menu"),
      templateType = menu.type == 'page' ? 'menuPageEditTemplate' : 'menuLinkEditTemplate';

    var html = Mustache.to_html($("#" + templateType).html(), menu);
    $("#menuEditContent").html(html);
  });
});
