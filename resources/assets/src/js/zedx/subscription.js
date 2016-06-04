$(document).ready(function() {
  $(".selectSubscriptionAdType").on("change", function() {
    var val = $(this).val();
    var id = $(this).data("id");
    if (val == 1) {
      $("#nbrSubscriptionAdType_" + id).removeClass("hide");
    } else {
      $("#nbrSubscriptionAdType_" + id).addClass("hide");
    }
  })
});
