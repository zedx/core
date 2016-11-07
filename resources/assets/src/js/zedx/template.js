$(document).ready(function() {
  var editableOptions = {
    success: function(response, newValue) {
      $(this).html(newValue);
      updatePreview();
    }
  };

  $('.template-block-title').editable(editableOptions);
  $('.template-element-class').editable(editableOptions);

  var token = $('meta[name="csrf-token"]').attr('content');
  var createNewColumn = function(grid) {
    var block = Mustache.to_html($("#TemplateNewBlockTemplate").html()),
      column = Mustache.to_html($("#TemplateColumnTemplate").html(), {grid:grid, block:block});
    return column;
  }

  var createNewRow = function(grid) {
    var column = createNewColumn(grid),
      row = Mustache.to_html($("#TemplateRowTemplate").html(), {column:column});
    return row;
  }

  $(document).on('click', '.template-addColumn', function() {
    var column = createNewColumn(2);
    $(this).closest('.template-editing').children(".template-tools").last().before(column);
    $('.template-block-title').editable(editableOptions);
    $('.template-element-class').editable(editableOptions);
    updatePreview();
  });

  $(document).on('click', '.template-addRow', function() {
    var row = createNewRow(12),
      prevElement = $(this).parent().prev();
    if (prevElement.hasClass('template-editable-region')) {
      prevElement.replaceWith(row);
    }else{
      prevElement.after(row);
    }
    $('.template-block-title').editable(editableOptions);
    $('.template-element-class').editable(editableOptions);
    updatePreview();
  });

  $(document).on('click', '.template-addNewRow', function() {
    var row = createNewRow(12);
    $('#template-canvas').prepend(row);
    $('.template-block-title').editable(editableOptions);
    $('.template-element-class').editable(editableOptions);
    updatePreview();
  });

  var fixRemovedRow = function(column) {
    if (column.length) {
      var subRows = column.children('.row.template-editing');
      if (subRows.length <= 1) {
        var block = Mustache.to_html($("#TemplateNewBlockTemplate").html());
        column.children(".template-tools").last().before(block);
        $('.template-block-title').editable(editableOptions);
        $('.template-element-class').editable(editableOptions);
      }
    }
  }

  $(document).on('click', '.template-removeRow', function() {
    var row = $(this).closest('.row.template-editing'),
      column = row.closest('.column.template-editing');

    fixRemovedRow(column);
    row.remove();
    updatePreview();
  });

  $(document).on('click', '.template-removeCol', function() {
    $(this).closest('.column.template-editing').remove();
    updatePreview();
  });

  var resize = function(element, type) {
    var grid = parseInt(element.data('template-grid')),
      old = grid;
    grid = type == 'minus' && grid > 1 ? grid - 1: grid;
    grid = type == 'plus' && grid < 12 ? grid + 1: grid;
    element.data('template-grid', grid);
    element.removeClass('col-md-' + old).addClass('col-md-' + grid);
  }

  $(document).on('click', '.template-colIncrease', function() {
    var column = $(this).closest('.template-editing');
    resize(column, 'plus');
    updatePreview();
  });

  $(document).on('click', '.template-colDecrease', function() {
    var column = $(this).closest('.template-editing');
    resize(column, 'minus');
    updatePreview();
  });

  $('#template-title').on('input', function() {
    updatePreview();
  });

  var sortColumnItem;
  $( "#template-canvas" ).sortable({
    items: ".row.template-editing",
    placeholder: "alert-warning",
    forcePlaceholderSize: true,   opacity: 0.7,  revert: true,
    tolerance: "pointer",
    cursor: "move",
    handle: ".template-moveRow",
    start: function(e, ui) {
      var row = ui.item.closest('.row.template-editing');
      sortColumnItem = row.closest('.column.template-editing');
      console.log("start", sortColumnItem);
    },
    stop: function(e, ui) {
      console.log("stop", sortColumnItem);
      fixRemovedRow(sortColumnItem);
      updatePreview();
    }
  });

  $( ".row.template-editing" ).sortable({
    items: ".column.template-editing",
    placeholder: "alert-warning",
    forcePlaceholderSize: true,   opacity: 0.7,  revert: true,
    tolerance: "pointer",
    cursor: "move",
    handle: ".template-moveCol",
    stop: function(e, ui) {
      updatePreview();
    }
  });

  /* Generate */
  var generate = {
    _json: {},
    _html: '',
    _identifier: '',
    _elementId: 'template-canvas',
    rand: function() {
      var time = new Date().getTime(),
        n=Math.floor(Math.random()*11),
        k = Math.floor(Math.random()* 1000000),
        rand = n + '-' + k + '-' + time;
      return rand;
    },
    toHtml: function() {
      generate._html = '<div id="page-blocks" class="show-grid">';
      generate.toJson();
      generate._html += '</div>';
      return generate._html;
    },
    toJson: function() {
      var element = $('#' + generate._elementId);
      var identifier = element.data('identifier');
      identifier = identifier ? identifier : generate.rand();
      generate._json = {
        "@attributes":{
          "identifier": identifier,
          "title": $('#template-title').val()
        },
        row: generate._getRows(element)
      }
      return generate._json;
    },
    _getRows: function(element) {
      var elRows = element.children('.row');
      var rows = [];
      elRows.each(function() {
        generate._html += '<div class="row">';
        var elRow = $(this),
          classes = elRow.find('.template-element-class').first().text();

        rows.push({
          "@attributes": {
            "class": classes
          },
          col: generate._getCols(elRow)
        });

        generate._html += '</div>';
      })

      return rows;
    },
    _getCols: function(elRow) {
      var elCols = elRow.children('.column');
      var cols = [];
      elCols.each(function() {
        var elCol = $(this),
          grid = elCol.data('template-grid'),
          classes = elCol.find('.template-element-class').text(),
          col = {
            "@attributes": {
              "grid": grid,
              "class": classes
            }
          },
          colBlock = elCol.find('> .template-editable-region');

        generate._html += '<div class="block-to-connect-edit col-md-' + grid + ' block " data-template-grid="' + grid + '">';
        if (colBlock.length) {
          col['block'] = generate._getBlock(colBlock);
        }else{
          col['row'] = generate._getRows(elCol);
        }
        generate._html += '</div>';
        cols.push(col);
      });

      return cols;
    },
    _getBlock: function(colBlock) {
      var blockIdentifier = colBlock.data('template-identifier'),
        blockTitle = colBlock.find('.template-block-title').html();

      blockIdentifier = blockIdentifier ? blockIdentifier : generate.rand();

      generate._html += '<div class="template-block-content">';
      generate._html += '  <a href="javascript:void(0)">';
      generate._html += '    <div class="wrapper text-center">';
      generate._html += '      <h5>' + blockTitle + '</h5>';
      generate._html += '    </div>';
      generate._html += '  </a>';
      generate._html += '</div>';

      return {
        "@attributes":{
          "identifier": blockIdentifier,
          "title": blockTitle
        }
      };
    }
  }

  var updatePreview = function () {
    $('#templateSkeleton').val(JSON.stringify(generate.toJson()));
    $('#template-render-preview').html(generate.toHtml());
  }
  updatePreview();

  /* End Generate */
});
