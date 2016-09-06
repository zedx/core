$(document).ready(function() {
    $('.provider-client').editable({
        success: function(response, newValue) {
            var providerName = $(this).closest('tr').data('provider');
            $('#client_id_' + providerName).val(newValue);
        }
    });

    $('.provider-secret').editable({
        success: function(response, newValue) {
            var providerName = $(this).closest('tr').data('provider');
            $('#secret_key_' + providerName).val(newValue);
        }
    });

    var makeSmartPreview = function($this) {
        if (typeof (FileReader) == "undefined") {
            alert("This browser does not support HTML5 FileReader.");

            return;
        }

        var src,
            html,
            regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/,
            files = $this[0].files;

        $(files).each(function (index, file) {
            if (regex.test(file.name.toLowerCase())) {
              var reader = new FileReader();
              reader.onload = function (e) {
                src = e.target.result;
                html = '<img src="' + src + '" class="preview-logo" />';
                $this.closest('.parent').find('.image').html(html);
              }
              reader.readAsDataURL(file);
            } else {
              alert(file.name + " is not a valid image file.");
              return false;
            }
        });
    }

    $('.edit-image-setting').on("change", function () {
        makeSmartPreview($(this));
    });
});
