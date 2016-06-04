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
  String.prototype.trimToLength = function(m) {
    return (this.length > m) ? jQuery.trim(this).substring(0, m) + "..." : this;
  };

  $('#dialogAdtype').on('show.bs.modal', function (event) {
    var target = $(event.relatedTarget),
      adtype = target.data("adtype");

    adtype.adId = target.data("ad-id");
    var html = Mustache.to_html($("#adTypePersonnalizeTemplate").html(), adtype);
    $("#adTypePersonnalize").html(html);
  });

  var $adFields = $("#adFields");
  var adFields = $adFields.data("fields");
  var currency = $adFields.data("currency");

  var renderSelectbox = function(field) {
    $.each(field.select, function(key, option) {
      field.select[key].parentId = field.id;
      option.unit = field.unit;
      option.selected = adFields.hasOwnProperty(field.id) && in_array(option.id, $.makeArray(adFields[field.id].value));
    });

    var templateType = field.type == 3 ? 'multiple' : 'selectbox';

    return Mustache.to_html($("#adFieldsTemplate_" + templateType).html(), field);
  }

  var renderInput = function(field) {
    field.input = true;
    if (adFields.hasOwnProperty(field.id)) {
      field.value = adFields[field.id].value;
    }
    if (field.unit !== "" && field.input === true) {
      field.inputGroup = true;
      field.input = false;
    } else {
      field.inputGroup = false;
    }

    return Mustache.to_html($("#adFieldsTemplate_input").html(), field);
  }

  var syncFields = function(fields) {
    $("#adFields").html('');
    var contentHtml;

    $.each(fields, function(key, field) {
      contentHtml = '';
      if (field.unit == '{currency}') {
        field.unit = currency;
      }
      if (in_array(field.type, ['1', '2', '3'])) {
        contentHtml = renderSelectbox(field);
      }else{
        contentHtml = renderInput(field);
      }

      $("#adFields").append(contentHtml);
    });

    $(".appendSelect2").select2();
  }

  var updateFields = function(url) {
    if (!url) {
      $("#adFields").html("");
    }else{
      $.getJSON( url, function( fields ) {
        syncFields(fields);
      });
    }
  }


  $("#category_id").change(function() {
    var url = $("option:selected", this).data("category-api-url");
    updateFields(url);
  });

  $("#category_id").trigger("change");

  /*
  $('.summernote').summernote({
  onkeyup: function(e) {
    $(".summernote").val($(".summernote").code());
  },
  height: 180,
  focus: false,
  tabsize: 2
  });
*/

  function userFormatResult(data) {
    icon_status = (data.status == 1) ? "user" : "briefcase";
    var markup = '<div class="row"><div class="col-md-12 profile-info"><strong><i class="fa fa-' + icon_status + '"></i> </strong> <small>@' + data.name + '</small><p><i class="fa fa-envelope"></i> ' + data.email + '</p></div></div>';
    return markup;
  }

  function userFormatSelection(data) {
    if (!data.name && data.text) {
      return data.text;
    }
    return data.name;
  }

  /* select2 User */
  var $user = $("#zedx-ad-user");
  $user.select2({
    ajax: {
      url: $user.data("url"),
      dataType: 'json',
      data: function (params) {
        return {
          q: params.term
        };
      },
      processResults: function (data, params) {
        return {
          results: data.data,
        };
      },
      cache: true
    },
    escapeMarkup: function (markup) { return markup; },
    minimumInputLength: 1,
    placeholder: $user.data('placeholder'),
    templateResult: userFormatResult,
    templateSelection: userFormatSelection,
  });

  // Geolocalisation Google Maps autocomplete
  function GeoFormatResult(data) {
    var markup = '<div class="row"><div class="col-md-12 profile-info"><strong><i class="fa fa-map-marker"></i> ' + data.formatted_address + '</strong></div></div>';
    return markup;
  }

  $("select").on("select2:unselecting", function (e) {
    $(this).select2("val", "");
    e.preventDefault();
  });

  function GeoFormatSelection(data) {
    if (!data.formatted_address && data.text) {
      return data.text;
    }
    if (data.formatted_address) {
      return data.formatted_address.trimToLength(55);
    }
  }

  /* Select2 Geolocation */
  var $geolocation = $("#zedx-ad-geolocation");
  $geolocation.select2({
    ajax: {
      url: 'http://maps.google.com/maps/api/geocode/json',
      dataType: 'json',
      data: function (params) {
        return {
          address: params.term,
          sensor: false
        };
      },
      processResults: function (data, params) {
        return {
          results: $.map(data.results, function(obj) {
            obj.id = JSON.stringify(obj);
            return obj;
          })
        };
      },
      cache: true
    },
    escapeMarkup: function (markup) { return markup; },
    minimumInputLength: 1,
    allowClear: true,
    placeholder: $geolocation.data('placeholder'),
    templateResult: GeoFormatResult,
    templateSelection: GeoFormatSelection,
  });

  /* FindMe */
  $("#findme").click(function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var $request = $.ajax({
          url: "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" + position.coords.latitude + "," + position.coords.longitude
        });
        $request.then(function (data) {
          var results =data.results[0];
          // Create the DOM option that is pre-selected by default
          var option = new Option(results.formatted_address, JSON.stringify(results), true, true);
          // Append it to the select
          $geolocation.append(option);
          $geolocation.trigger("change");
        });
      });
    }
  });

  // Photos

  var photos = $("#photos").data("photos");
  if (photos && photos.length > 0) {
    var html = Mustache.to_html($("#photoTemplate").html(), photos);
    $("#photos").append(html);
  }

  var appendNewPhoto = function() {
    var $photos = $("#photos");
    var emptyPhoto = $photos.children('[data-empty-photo]').length;
    if ($photos.data("can-add-photo") == '1' && !emptyPhoto) {
      var nbrMax = parseInt($photos.data("max-photos"));
      nbrMax = nbrMax - $photos.children('[data-photo]').length;
      if (nbrMax > 0) {
        var html = Mustache.to_html($("#newPhotoTemplate").html());
        $("#photos").append(html);
      }
    }
  }
  appendNewPhoto();

  var updatePhotoPreview = function(src, $this) {
    var emptyPhotoAttr = $this.attr('data-empty-photo');
    if (typeof emptyPhotoAttr !== typeof undefined && emptyPhotoAttr !== false) {
      $this.removeAttr('data-empty-photo');
      $this.attr('data-photo', '');
      $this.find('.image').html('<center><img class="img-rounded" src="' + src + '" width="100" height="60" /></center');
      var $uploadButton = $this.find('.addAdPhotos').parent();
      $uploadButton.removeClass('btn-block');
      $uploadButton.children('.text').text('');
      var deleteContent = '<div class="btn-group">';
      deleteContent += '<button type="button" class="btn btn-xs btn-danger remove-photo"><i class="fa fa-remove"></i></button>';
      deleteContent += '</div>';

      $(deleteContent).insertBefore($uploadButton.parent());
    }else{
      $this.find('.image').html('<center><img class="img-rounded" src="' + src + '" width="100" height="60" /></center');
    }
  }

  var makeSmartPreview = function($this) {
    if (typeof (FileReader) != "undefined") {
      var src,
        html,
        regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/,
        files = $this[0].files;

      $(files).each(function (index, file) {
        if (regex.test(file.name.toLowerCase())) {
          var reader = new FileReader();
          reader.onload = function (e) {
            src= e.target.result;
            var existingPhoto = $this.closest('.uploadedPhoto');
            updatePhotoPreview(src, existingPhoto);
            appendNewPhoto();
          }
          reader.readAsDataURL(file);
        } else {
          alert(file.name + " is not a valid image file.");
          return false;
        }
      });

    } else {
      alert("This browser does not support HTML5 FileReader.");
    }
  }
  $(document).on("change", ".addAdPhotos", function () {
    makeSmartPreview($(this));
  });

  $(document).on("click", ".remove-photo", function() {
    $(this).closest('[data-photo]').remove();
    appendNewPhoto();
  });

  // Videos
  var getNbrVideoToAdd = function() {
    var $videos = $("#videos"),
      nbrMax = parseInt($videos.data("max-videos")),
      nbrMax = nbrMax - $videos.children('[data-video]').length;

    return nbrMax;
  }
  var youtube_parser = function(url) {
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match && match[7].length == 11) {
      return match[7];
    } else {
      return null;
    }
  }
  var _videos = [];
  var videos = $("#videos").data("videos");
  if (videos && videos.length > 0) {
    var html = Mustache.to_html($("#videoTemplate").html(), videos);
    $("#videos").append(html);
    $('.js-lazyYT').lazyYT();
  }

  if (getNbrVideoToAdd() == 0) {
    $('#form-add-video').addClass('hide');
  }

  $("#add_video").on("click", function() {
    var nbrMax = getNbrVideoToAdd();
    if (nbrMax > 0) {
      var videoUrl = $('#inputVideo').val();
      if ((videoId = youtube_parser(videoUrl)) !== null && _videos.indexOf(videoId) === -1) {
        var html = Mustache.to_html($("#videoTemplate").html(), [{
          link: videoId
        }]);
        _videos.push(videoId);
        $("#videos").append(html);
        $('.js-lazyYT').lazyYT();
      }
      $('#inputVideo').val("");
      if (getNbrVideoToAdd() == 0) {
        $('#form-add-video').addClass('hide');
      }
    }
  });

  $(document).on("click", ".remove-video", function() {
    var videoId = $(this).data('video-link');
    var index;
    $('#video_' + videoId).remove();
    if ((index = _videos.indexOf(videoId)) > -1) {
      _videos.splice(index, 1);
    }
    $('#form-add-video').removeClass('hide');
  });

  /* Moderate */

  String.prototype.capitalizeFirstLetter = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
  }

  /* Ban */
  $(document).on("click", "#adBanReasonList tr", function(){
    var checkbox = $(this).find(':checkbox'),
      value = checkbox.prop("checked"),
      newStatus = value ? "uncheck" : "check";
    checkbox.iCheck(newStatus);
  });

  $(document).on("input", '#adBanNewReasonList input[type=text]', function(){
    var value = this.value,
      newStatus = value ? "check" : "uncheck",
      checkbox = $(this).closest("tr").find(':checkbox');

    checkbox.val(value);
    checkbox.iCheck(newStatus);
  });

  $("#addNewAdBanReason").on("click", function() {
    var html = Mustache.to_html($("#adBanNewReasonTemplate").html());
    $("#adBanNewReasonList").append(html);
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });

  /* Rest AdBan Modal */
  $('body').on('hidden.bs.modal', '#dialogBanAd', function () {
    $("#adBanReasonList input[type='checkbox']").iCheck("uncheck");
    $("#adBanNewReasonList").empty();
  });
})
