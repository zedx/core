$(document).ready(function() {
    var token = $('meta[name="csrf-token"]').attr('content');

    var action = {
        title: null,
        params: null,
        text: null,
        route: null,
        element: null,
        listType: null,
        remove: function(force) {
            if (action.listType != "all" || force) {
                var $target = action.element;
                $target.hide('slow', function(){
                    $target.remove();
                });
            }
        },
        __pushToServer: function(ajaxType, callback) {
            var type = ajaxType ? ajaxType : "PUT",
                data = "_token=" + token;

            data = action.params ? data + "&" + action.params : data;

            $.ajax({
              url: action.route,
              type: type,
              data: data,
              success: callback
            });
        },
        __exec: function(type, force, ajaxType) {
            action.__pushToServer(ajaxType, function() {
                new PNotify({
                  title: action.title,
                  text: action.text,
                  type: type,
                  addclass: "stack-custom2",
                  delay: 1000,
                });
                action.remove(force);
            });
        },
        elementHold: function() {
            action.__exec('info');
        },
        elementAccept: function() {
            action.__exec('success');
        },
        elementBan: function() {
            action.__exec('notice');
        },
        elementDelete: function() {
            action.__exec('error', true, 'DELETE');
        }
    }

    var getCheckedElements = function() {
        var ids = [];
        $(".checkbox-auto-toggle input[type='checkbox']:checked").each(function(key, el) {
            ids.push($(el).closest( "[data-element-parent-action]" ).data('id'));
        });

        if (ids.length) {
            return ids.join();
        }

        return false;
    }

    var elementAction = function(obj, params, isMany) {
        var $this = $(obj),
            element = isMany ? getJqueryCheckedElements() : $this.closest( "[data-element-parent-action]" );
            elementId = isMany ? getCheckedElements() : element.data('id'),
            type = isMany ? $this.data('elements-action') : $this.data('element-action');

        if (type && elementId) {
            action.text = isMany ? getElementsText($this.data('elements-action-text'), elementId) : $this.data('element-action-text');
            action.route = isMany ? getElementsRoute($this.data('elements-action-route'), elementId) : $this.data('element-action-route');
            action.element = element;
            action.params = params;
            action.title = isMany ? $this.data('elements-action-title') : element.data('title');
            action.listType = $("#elementList").data('type');

            action["element" + type.capitalizeFirstLetter()]();
        }
    }

    var getJqueryCheckedElements = function() {
        return $(".checkbox-auto-toggle input[type='checkbox']:checked").closest( "[data-element-parent-action]" );
    }

    var getElementsText = function(text, elementsId) {
        var nbr = elementsId.split(",").length;
        return text.replace('{nbr}', nbr);
    }
    var getElementsRoute = function(route, elementsId) {
        return route.replace('_elements_', elementsId);
    }

    $(document).on("click", '[data-element-action]', function() {
        elementAction(this);
    });

    $(document).on("click", '[data-elements-action]', function() {
        if (getCheckedElements() == false) {
            $($(this).data('target')).find(".modal-message").html("Veuillez selectionner un élément.");
            $($(this).data('target')).find("#confirm").hide();
        }else{
            $($(this).data('target')).find("#confirm").show();
        }
        elementAction(this, false, true);
    });

    $('#dialogBanAd').find('.modal-footer #confirm').on('click', function(){
        var $element = $(this).data('parent').children(),
            params = $("#banAdForm").formSerialize();

        var isMany = $element.attr('data-element-action') == undefined;
        var constructor = isMany ? 'elements-action' : 'element-action';

        $element.data(constructor, "ban");
        elementAction($element, params, isMany);
        $element.data(constructor, "");
    });

    /* Delete */

    var deleteResource = function($element, forceDelete) {
        var isMany = $element.attr('data-element-action') == undefined;
        var constructor = isMany ? 'elements-action' : 'element-action';
        var params = forceDelete ? '__forceDelete=true': false;

        $element.data(constructor, "delete");
        elementAction($element, params, isMany);
        $element.data(constructor, "");
    }

    $('#confirmDeleteAction').find('.modal-footer #confirmToTrash').on('click', function(){
        var $element = $(this).parent().children('#confirm').first().data('parent').children();
        deleteResource($element, false);
    });

    $('#confirmDeleteAction').find('.modal-footer #confirm').on('click', function(){
        var $element = $(this).data('parent').children();
        deleteResource($element, true);
    });
});
