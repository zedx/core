$(document).ready(function() {
  var zedxUpdater = {
    progress: function(percent) {
      $('#updater-building').val(percent).trigger('change');
    },
    startTask: function(url) {
      $('#updater-log').html("");

      var deferred = jQuery.Deferred();
      var es = new EventSource(url);

      //a message is received
      es.addEventListener('message', function(e) {
        var result = JSON.parse(e.data);
        zedxUpdater.addLog(result);

        if (e.lastEventId == 'COMPLETE') {
          es.close();
          zedxUpdater.progress(100);
          deferred.resolve(result);
        } else if (e.lastEventId == 'ERROR') {
          es.close();
          deferred.reject(result);
        } else {
          zedxUpdater.progress(result.progress);
        }
      });
      es.addEventListener('error', function(e) {
        var result = JSON.parse(e.data);

        zedxUpdater.addLog(result);
        es.close();
        deferred.reject({
          message: 'Something going wrong ...'
        });
      });
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
      force = $this.data('force') == '1' ? 'force=true&' : '',
      url = $this.data('update-url') + '?install=true&' + force + '_token=' + token;

    $this.prop('disabled', true);
    $('.fa-refresh').addClass('fa-spin');

    zedxUpdater.startTask(url).then(function() {
      $('.fa-refresh').removeClass('fa-spin');
    }, function() {
      $('.fa-refresh').removeClass('fa-spin');
    });
  })
});
