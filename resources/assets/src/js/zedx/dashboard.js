$(function() {
  var token = $('meta[name="csrf-token"]').attr('content');

  $('.dashboard-widget-name').editable({
      success: function(response, newValue) {

          var $this = $(this),
              url = $this.closest('section').data('url'),
              name = $this.data('name'),
              data = {
                  _token: token,
              };
          data[name] = newValue;

          $.ajax({
              method: "PUT",
              url: url,
              dataType: 'JSON',
              data: data
          });
      }
  });

  $('[data-resize-widget]').on('click', function() {
  	var $this = $(this),
  		type = $this.data('resize-widget'),
      section = $this.closest('section')
      url = section.data('url'),
      size = parseInt(section.data('size'));

      size = type == 'minus' && size > 1 ? size - 1: size;
      size = type == 'plus' && size < 12 ? size + 1: size;

  		$.ajax({
          method: "PUT",
          url: url,
          dataType: 'JSON',
          data: {
          	_token: token,
          	size: size
          }
      }).done(function() {
	    	section.data('size', size);
	    	section.removeClass().addClass('col-md-' + size);
      });
  });
});
