$(document).ready(function() {
    $("#zedx-fields").selectr({
        width: '100%',
        maxListHeight: '250px',
        tooltipBreakpoint: 25
    });

    var onChange = function(ev) {
        var jQobj = $(this);
        var nbrUse = jQobj.closest("tr").find('.code_max').val();
        var date = jQobj.closest("tr").find('.code_end_date').val();
        var diffDay = moment(date, 'DD/MM/YYYY').diff(moment(), 'days');
        validateCode(diffDay, nbrUse, jQobj);
    }

    var validateCode = function(diffDay, nbrUse, jQobj) {
        var validateMsg = jQobj.closest("tr").find('.code_validate_msg').first();
        var msgs = $("#codes").data("code-validate-msg");

        if (diffDay < 0) {
            validateMsg.html('<small class="label bg-red">' + msgs.expired + '</small>');
        } else if (!isNaN(nbrUse) && nbrUse <= 0) {
            validateMsg.html('<small class="label bg-orange">' + msgs.reached + '</small>');
        } else {
            validateMsg.html('<small class="label bg-green">' + msgs.validate + '</small>');
        }
    }

    $(document).on("keyup", ".code_max", onChange);
    $(document).on("change", ".code_end_date", onChange);

    $(".list-group").sortable({
        handle: '.zx-move-field',
        start: function(event, ui) {
            ui.item.startPosition = ui.item.index();
        },
        stop: function(event, ui) {
            var El = $("#zedx-fields option").eq(ui.item.startPosition);
            var cpy = El.clone();
            if (El.is(':selected')) {
                cpy.attr("selected", "selected");
            }
            El.remove();
            if (ui.item.index() === 0)
                $("#zedx-fields").prepend(cpy);
            else
                $("#zedx-fields option").eq(ui.item.index() - 1).after(cpy);

        }
    });

    var codeId = 1;

    var codes = $("#codes").data("codes");
    if (codes && codes.length > 0) {
        var html = Mustache.to_html($("#codesTemplate").html(), codes);
        $("#codes").append(html);
    }

    $("#add_code").on("click", function() {
        var html = Mustache.to_html($("#codesTemplate").html(), [{
            code: Math.random().toString(36).substr(2, 5).toUpperCase(),
            end_date: moment().format('DD/MM/YYYY'),
            max: "5",
            id: codeId
        }]);
        codeId++;

        $("#codes").append(html);
        $(".datepicker").datepicker();
    });

    $(document).on("click", "#remove-thumbnail", function() {
        var $this = $(this);
        $('#oldThumbnail').val("");
        $('#thumbnail').val("");
        $this.closest('.parent').find('.image').html("");
        $(this).hide();
    });

    $(document).on("click", ".remove-code", function() {
        $(this).closest("tr").remove();
    });

    $("#is_private").change(function() {
        if ($(this).val() == 1) {
            $('#codes').removeClass("hide");
            $('#newcodes').removeClass("hide");
        } else {
            $('#codes').addClass("hide");
            $('#newcodes').addClass("hide");
        }
    });

    $('#nestableList').nestable();

    $("#is_private").trigger("change");
});
