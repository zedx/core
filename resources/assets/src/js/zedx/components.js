$(function() {
  var token = $('meta[name="csrf-token"]').attr('content');
  var url = $('#upload-components').data('url');
  // Get the template HTML and remove it from the doument
  var previewNode = document.querySelector("#template");
  if (previewNode) {
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);
  }
  if (url) {
    var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
      url: url, // Set the url
      params: {
        _token: token
      },
      thumbnailWidth: 80,
      thumbnailHeight: 80,
      parallelUploads: 20,
      previewTemplate: previewTemplate,
      autoQueue: false, // Make sure the files aren't queued until manually added
      previewsContainer: "#previews", // Define the container to display the previews
      clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
    });
    myDropzone.on("addedfile", function(file) {
      // Hookup the start button
      file.previewElement.querySelector(".start").onclick = function() {
        myDropzone.enqueueFile(file);
      };
    });
    // Update the total progress bar
    myDropzone.on("totaluploadprogress", function(progress) {
      $("#total-progress .progress-bar").css("width", progress + "%")
    });
    myDropzone.on("sending", function(file) {
      // Show the total progress bar when upload starts
      $("#total-progress").fadeIn("slow");
      // And disable the start button
      file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
    });
    // Hide the total progress bar when nothing's uploading anymore
    myDropzone.on("queuecomplete", function(progress) {
      $("#total-progress").fadeOut("slow");
    });
    // Setup the buttons for all transfers
    // The "add files" button doesn't need to be setup because the config
    // `clickable` has already been specified.
    $("#actions .start").on('click', function() {
      myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    });
    $("#actions .cancel").on('click', function() {
      myDropzone.removeAllFiles(true);
    });
  }
  // Install component
  $('.zedx-install-component').on('click', function() {
    var $this = $(this),
      url = $this.data('url'),
      type = $this.data('component-type'),
      slug = $this.data('component-slug');

      $this.prop('disabled', true);

      $this.children().first()
        .removeClass('fa-download')
        .addClass('fa-spinner fa-spin');

    $.post(url, {_token: token}, function(data) {
      console.log( "success", data );

      $this.children().first()
        .removeClass('fa-spinner fa-spin')
        .addClass('fa-check');

      $this.removeClass('btn-primary').addClass('btn-success');
      //$this.remove();
    })
    .fail(function(data) {
      console.log( "error", data );
    })
  })
});
