$(document).ready(function() {
  $(document).on("change", "#can_add_pic", function() {
    var value = $(this).val();
    if (value == 1) {
      $("[data-check-type='photo']").removeClass("hide");
    } else {
      $("[data-check-type='photo']").addClass("hide");
    }
  });
  $(document).on("change", "#can_add_video", function() {
    var value = $(this).val();
    if (value == 1) {
      $("[data-check-type='video']").removeClass("hide");
    } else {
      $("[data-check-type='video']").addClass("hide");
    }
  });
});
