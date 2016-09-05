$(document).ready(function() {
  var zedxUpdater = {
    progress: function(percent) {
      $('#updater-building').val(percent).trigger('change');
    },
    startTask: function(url) {
      $('#updater-log').html("");

      var deferred = jQuery.Deferred();

      var es = new EventSource(url);

      var progressHandler = function(e) {
          var result = JSON.parse( e.data );

          zedxUpdater.addLog(result);
          zedxUpdater.progress(result.progress);
      }

      var completeHandler = function(e) {
          var result = JSON.parse(e.data);

          zedxUpdater.addLog(result);
          es.close();
          zedxUpdater.progress(100);

          deferred.resolve(result);
      }

      var errorHandler = function(e) {
          var result = JSON.parse(e.data);

          zedxUpdater.addLog(result);
          es.close();

          deferred.reject({
            message: 'Something going wrong ...'
          });
      }

      es.addEventListener('progress', progressHandler, false);
      es.addEventListener('complete', completeHandler, false);
      es.addEventListener('error', errorHandler, false);

      return deferred.promise();
    },
    addLog: function(result) {
      $('#updater-message').html(result.message);
      $('#updater-log').append("[ " + result.time + " ] " + result.message + "<br />");
    }
  }

  $('#start-zedx-updater').on('click', function() {

    var token = $('meta[name="csrf-token"]').attr('content'),
      $this = $(this),
      force = $this.data('force') == '1' ? '&force=true' : '',
      namespace = $this.data('namespace'),
      url = $this.data('update-url') + '?namespace=' + namespace + '&_token=' + token + force;

    $this.prop('disabled', true);
    $('.fa-refresh').addClass('fa-spin');

    zedxUpdater.startTask(url).then(function() {
      $('.fa-refresh').removeClass('fa-spin');
    }, function() {
      $('.fa-refresh').removeClass('fa-spin');
    });
  })
});
