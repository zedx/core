$(function() {
    $(".select2").select2();

    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });

    $(".knob").knob({
        format : function (value) {
            return value + '%';
        }
    });

    $(".datepicker").datepicker();

    $("[data-mask]").inputmask();

    $(".bootstrap-switch").bootstrapSwitch({
        onInit: function(event) {
            var $this = $("#"+$(event.currentTarget).attr('id'));
            var state = $this.prop('checked');
            $this.prop('checked', true);
            $this.val(+state);
        },
        onSwitchChange: function(event, state) {
            var $this = $("#"+$(event.currentTarget).attr('id'));
            $this.prop('checked', true);
            $this.val(+state);
        }
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".checkbox-auto-toggle input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".checkbox-auto-toggle input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    $('.minicolors').minicolors({
        control: 'wheel',
        inline: false,
        theme: 'bootstrap'
    });

    $('[data-toggle="popover"]').popover();

    $('.js-lazyYT').lazyYT();

    $('#nestableList').nestable({
        dropCallback: function(details) {
            var rightId = details.sourceEl.next().data("id"),
                leftId = details.sourceEl.prev().data("id"),
                parentId = details.destId,
                id = details.sourceId;

            if (id) {
                var url = $('#nestableList').data('url') + '/' + id + '/order';
                var token = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    method: "PUT",
                    url: url,
                    dataType: 'JSON',
                    data: {
                        _token: token,
                        parentId: parentId,
                        rightId: rightId,
                        leftId: leftId
                    }
                });
            }
        }
    });

    $('#nestable-menu').on('click', function(e) {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('#nestableList').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('#nestableList').nestable('collapseAll');
        }
    });

    //Dropzone.autoDiscover = false;

    // Dialog show event handler
    $('.confirmationDialog').on('show.bs.modal', function (e) {
        $message = $(e.relatedTarget).attr('data-message');
        $(this).find('.modal-body .modal-message').text($message);
        $title = $(e.relatedTarget).attr('data-title');
        $(this).find('.modal-title').text($title);

        // Pass form reference to modal for submission on yes/ok
        var parent = $(e.relatedTarget).parent();
        $(this).find('.modal-footer #confirm').data('parent', parent);
    });

    /*
    $('.sortable-list').sortable({
        connectWith: '.sortable-list',
        placeholder: 'placeholder',
        beforeStop: function (event, ui) {
            ui.item.replaceWith('<li class="sortable-item">Hello</li>');
        }
    });

    $( ".draggable" ).draggable({
        connectToSortable: ".sortable-list",
        helper: "clone",
        scroll:true,
        revert: false
    });
    */

});
