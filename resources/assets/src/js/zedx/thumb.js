$(document).ready(function() {
    var isValidImage = function(fileName) {
      var fileTypes = ['jpg', 'jpeg', 'gif', 'png', 'bmp'],
        extension = fileName.split('.').pop().toLowerCase();

      return fileTypes.indexOf(extension) > -1;
    }

    var makeSmartPreview = function($this) {
        if (typeof (FileReader) == "undefined") {
            alert("This browser does not support HTML5 FileReader.");

            return;
        }

        var src,
            html,
            files = $this[0].files;

        $(files).each(function (index, file) {
            if (isValidImage(file.name)) {
              var reader = new FileReader();
              reader.onload = function (e) {
                src = e.target.result;
                html = '<img src="' + src + '" class="preview-logo" />';
                $this.closest('.parent').find('.image').html(html);
                $('#remove-thumbnail').show();
              }
              reader.readAsDataURL(file);
            } else {
              alert(file.name + " is not a valid image file.");
              return false;
            }
        });
    }

    $('.edit-image-setting, .edit-image-category').on("change", function () {
        makeSmartPreview($(this));
    });
});
