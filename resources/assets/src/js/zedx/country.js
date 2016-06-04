$(document).ready(function () {

    var token = $('meta[name="csrf-token"]').attr('content');

    $('.map-swap').on('ifChecked ifUnchecked', function(event){
        var $this = $(this),
            url = $this.data('url'),
            isActivate = event.type == 'ifChecked' ? '1' : '0';

        $.ajax({
            method: "PUT",
            url: url,
            data: { is_activate: isActivate, _token:token }
        });
    });

    $('#ZxMap-submit').on('click', function(e) {
        var $this = $(this),
            url = $this.data('url'),
            fill = $("#ZxMap-fill").val(),
            stroke = $("#ZxMap-stroke").val(),
            animateFill = $("#ZxMap-animate-fill").val(),
            strokeWidth = $("#ZxMap-stroke-width").val();
            width = $("#ZxMap-width").val();
            height = $("#ZxMap-height").val();

        $.ajax({
            method: "PUT",
            url: url,
            data: {
                _token:token,
                fill: fill,
                stroke: stroke,
                width: width,
                height: height,
                'stroke-width': strokeWidth,
                'animate-fill': animateFill
            }
        }).done(function() {
            location.reload();
        }).fail(function(data) {
            $("#ZxMap-response").removeClass("hide");
            $('#ZxMap-response .response-text').html(data.responseText);
        });
    });

    $('.edit-map').on('click', function(e) {
        var $this = $(this);

        $("#ZxMap-response").attr("class", "hide");
        $("#ZxMap-fill").minicolors('value', $this.data("fill"));
        $("#ZxMap-stroke").minicolors('value', $this.data("stroke"));
        $("#ZxMap-animate-fill").minicolors('value', $this.data("animate-fill"));
        $("#ZxMap-stroke-width").val($this.data("stroke-width"));
        $("#ZxMap-width").val($this.data("width"));
        $("#ZxMap-height").val($this.data("height"));
        $("#countryFlag").attr('class', 'flag-icon flag-icon-' + $this.data('code'));
        $("#ZxMap-submit").data("url", $this.data("url"));
    });

    $('.upload-map').on('click', function(e) {
        $("#ZxMapUploadResponse").attr("class", "hide");
        $("#uploadMapForm").attr("action", $(this).data("url"));
        $("#countryFlagUpload").attr('class', 'flag-icon flag-icon-' + $(this).data('code'));
    });


    $('#uploadMapFile').change(function () {
        $("#uploadMapForm").ajaxSubmit({
            error: function(data ){
                $("#ZxMapUploadResponse").removeClass("hide");
                $('#ZxMapUploadResponse .response-text').html(data.responseText);
            },
            success:function(e){
                location.reload();
            }
       });
    })

 });
