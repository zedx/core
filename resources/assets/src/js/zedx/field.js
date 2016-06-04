$(document).ready(function() {

  function in_array(needle, haystack) {
    var key = '';

    for (key in haystack) {
      if (haystack[key] == needle) {
        return true;
      }
    }

    return false;
  }

  $(".zedx-list-options").sortable({
    handle: '.zx-move-option'
  });

  /* Field type template */
  $fieldType = $("#field-type");
  function templateTypeResult(option) {
    if (!option.id) {
      return option.text;
    }
    var data = option.text.split('|'),
      adText = data[0],
      searchText = data[1];

    var $option = $(
      '<div class="row">' +
        '<div class="col-xs-1">' +
        '<i class="fa fa-paper-plane-o" style="font-size:25px"></i>' +
        '</div>' +
        '<div class="col-xs-5">' +
          '<div><b>' + adText + '</b></div>' +
          '<div><small>' + $fieldType.data('trans-ad') + '</small></div>' +
        '</div>' +
        '<div class="col-xs-1">' +
        '<i class="fa fa-search" style="font-size:25px"></i>' +
        '</div>' +
        '<div class="col-xs-5">' +
          '<div><b>' + searchText + '</b></div>' +
          '<div><small>' + $fieldType.data('trans-search_engine') + '</small></div>' +
        '</div>' +
      '</div>'
    );
    return $option;
  };

  function templateTypeSelection(option) {
    if (!option.id) {
      return option.text;
    }
    var data = option.text.split('|'),
      adText = data[0],
      searchText = data[1];

    var $option = $(
      '<div class="row">' +
        '<div class="col-md-6">' +
          '<i class="fa fa-paper-plane-o pr10"></i> ' + adText +
        '</div>' +
        '<div class="col-md-6">' +
          '<i class="fa fa-search pr10"></i> ' + searchText +
        '</div>' +
      '</div>'
    );
    return $option;
  };

  $fieldType.select2({
    templateResult: templateTypeResult,
    templateSelection: templateTypeSelection
  });

  var toggleOptions = function(status) {
    if (status) {
      toggleConfigInputNumeric(false);
      $("#options").removeClass("hide");
    }else{
      $("#options").addClass("hide");
    }
  }

  var toggleConfigInputNumeric = function(status) {
    if (status) {
      toggleOptions(false);
      $("#configInputNumeric").removeClass("hide");
    }else{
      $("#configInputNumeric").addClass("hide");
    }
  }

  $("#is_price").change(function() {
    if ($(this).val() == 1) {
      $('#field-type').val('4').attr('disabled', true).trigger("change");
      $('#fieldController').append('<input type="hidden" id="hidden_type" name="type" value="4" />');
    } else {
      $('#field-type').attr('disabled', false);
      $('#hidden_type').remove();
    }
  });

  $("#field-type").change(function() {
    $('input[name=is_in_search]').iCheck('enable');
    if (in_array(this.value, ['1', '2', '3'])) {
      toggleOptions(true);
    } else {
      toggleOptions(false);
      toggleConfigInputNumeric(false);
      if (this.value == '4') {
        toggleConfigInputNumeric(true);
      }else if (this.value == '5') {
        $('input[name=is_in_search]').iCheck('disable');
        $('input[name=is_in_search]').iCheck('uncheck');
      }
    }
  });

  var options = $("#options").data("options");
  var optionId = 1;

  if (options && options.length > 0) {
    var html = Mustache.to_html($("#optionsTemplate").html(), options);
    $(".zedx-list-options").append(html);
  }

  $("#add_option").on("click", function() {
    console.log("clicked")
    var html = Mustache.to_html($("#optionsTemplate").html(), [{name:"", id:"n"+optionId}]);
    optionId++;
    $(".zedx-list-options").append(html);
  });

  $(document).on("click", '.removeFieldOption', function() {
    var id = $(this).data('option-id');
    $('#option_f' + id).remove();
  });

  $("#field-type").trigger("change");
  $("#is_price").trigger("change");

});
