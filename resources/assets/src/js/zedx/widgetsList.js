$(document).ready(function() {
  var token = $('meta[name="csrf-token"]').attr('content');

  $.fn.widgetsList = function (settings) {
    this.sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        axis: 'y',
        zIndex: 999999,
        stop: function(event, ui) {
          settings.onSort(this, ui);
        }
    });
    return this.each(function (ev, child) {
      if (typeof $.fn.iCheck != 'undefined') {
        $('input', this).on('ifChecked', function (event) {
          var ele = $(this).parents("li").first();
          ele.toggleClass("disabled");
          settings.onCheck(ele);
        });

        $('input', this).on('ifUnchecked', function (event) {
          var ele = $(this).parents("li").first();
          ele.toggleClass("disabled");
          settings.onUncheck(ele);
        });
      } else {
        $('input', this).on('change', function (event) {
          var ele = $(this).parents("li").first();
          ele.toggleClass("disabled");
          settings.onCheck(ele);
        });
      }
    });
  };

  var changePosition = function(url, requestData){
    $.ajax({
        url: url,
        type: 'POST',
        data: requestData
    });
  };

  var widgetNodes = $("#widgetNodes").data("nodes");
  var widgetNodeRoute = $("#widgetNodes").data("route");
  if (widgetNodes && widgetNodes.length > 0) {
    $.each(widgetNodes, function(key, node) {
      node._route = widgetNodeRoute;
    });
    var html = Mustache.to_html($("#widgetNodesTemplate").html(), widgetNodes);
    $("#widgetNodes").html(html);
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });

    $(".widgets-list").widgetsList({
      onCheck: function (ele) {
        var url = widgetNodeRoute + '/' + ele.data('id') + '/swap';
        $.ajax({url: url, type: 'POST', data: {_token: token}});
        return ele;
      },
      onUncheck: function (ele) {
        var url = widgetNodeRoute + '/' + ele.data('id') + '/swap';
        $.ajax({url: url, type: 'POST', data: {_token: token}});
        return ele;
      },
      onSort: function(parent, ui) {
        var $parent = $(parent),
          entityName = $parent.data('entityname'),
          urlSort = $parent.data('urlsort'),
          $sorted = ui.item,
          $previous = $sorted.prev(),
          $next = $sorted.next();

        if ($previous.length > 0) {
          changePosition(urlSort, {
            _token: token,
            parentId: $sorted.data('parentid'),
            type: 'moveAfter',
            entityName: entityName,
            id: $sorted.data('id'),
            positionEntityId: $previous.data('id')
          });
        }else if ($next.length > 0) {
          changePosition(urlSort, {
            _token: token,
            parentId: $sorted.data('parentid'),
            type: 'moveBefore',
            entityName: entityName,
            id: $sorted.data('id'),
            positionEntityId: $next.data('id')
          });
        }
      }
    });
  }

  $('#addWidget').on('click', function() {
    var selectedWidget = $('#widgetsList').find(":selected:not([disabled])");
    if(selectedWidget.length) {
      $("#widgetNamespace").val(selectedWidget.data('namespace'));
      $("#widgetTitle").val(selectedWidget.data('title'));
      $("#widgetConfig").val(JSON.stringify(selectedWidget.data('config')));

      $("#widgetNodeForm").submit();
    }
  });

  $('.page-widget-name').editable({
      success: function(response, newValue) {
        var $this = $(this),
            url = $this.data('url'),
            widgetId = $this.data('widget-id'),
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
        }).always(function() {
          $("[data-widget-id='" + widgetId +"']").text(newValue);
        });
      }
  });

  $('#confirmWidgetAction').find('.modal-footer #confirm').on('click', function(){
      $(this).data('parent').submit();
  });
});
