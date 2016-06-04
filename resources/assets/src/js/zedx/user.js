$(document).ready(function() {

  if ($("#status").val() == 1) {
    $("#professionnal").removeClass("hide");
  }else{
    $("#professionnal").addClass("hide");
  }

  $("#status").change(function() {
    if ($(this).val() == 1) {
      $("#professionnal").removeClass("hide");
    } else {
      $("#professionnal").addClass("hide");
    }
  });

  $subAt = $("#subscribed_at");
  if ($subAt.val() == '') {
    $subAt.datepicker('setDate', new Date());
  }

  var updateAdType = function(adtypes) {
    if (adtypes && adtypes.length > 0) {
      var html = Mustache.to_html($("#adtypesTemplate").html(), adtypes);
      $("#adtypes").html(html);
    }
  }

  $("#subscriptions").on("change", function() {
    var adtypes = $(this).find(':selected').data('adtypes');
    updateAdType(adtypes);
  });

  var defaultAdtypes = $("#subscriptions").data("default-adtypes");
  updateAdType(defaultAdtypes);

});
