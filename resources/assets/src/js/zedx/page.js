$(document).ready(function() {
    $('#error-response').html("");
    var token = $('meta[name="csrf-token"]').attr('content');
    var setTemplate = function(templateId) {
            templateId = 'template_' + templateId;
            $('.template-new-schema').addClass("hide");
            $('#' + templateId).removeClass("hide");
        }
        //Template connect
    var selectedTemplate = $('#templatesList').find(":selected:not([disabled])");
    if (selectedTemplate.length) {
        setTemplate(selectedTemplate.val());
    }
    $("#templatesList").change(function() {
        setTemplate(this.value);
        unsetAllConnectedBlocks();
    });
    // Template preview
    var setPreviewTemplate = function(templateId) {
        templateId = 'template_preview_' + templateId;
        $('.template-preview-schema').addClass("hide");
        $('#' + templateId).removeClass("hide");
    }
    var selectedPreviewTemplate = $('#template_id').find(":selected:not([disabled])");
    if (selectedPreviewTemplate.length) {
        setPreviewTemplate(selectedPreviewTemplate.val());
    }
    $("#template_id").change(function() {
        setPreviewTemplate(this.value);
    });
    //ThemePartials
    var switchThemePartial = function($this, type) {
        var url = $this.data('url');
        $.ajax({
            url: url,
            type: type,
            data: {
                _token: token
            }
        });
    }
    $('.themepartial-input').on('ifChecked', function() {
        switchThemePartial($(this), 'POST');
    });
    $('.themepartial-input').on('ifUnchecked', function() {
        switchThemePartial($(this), 'DELETE');
    });
    var randomColor = function() {
        var letters = '0123456789ABCDEF'.split('');
        var c = '#'
        for (var i = 0; i < 6; i++) {
            c += letters[Math.floor(Math.random() * 16)];
        }
        return c;
        //return '#'+Math.floor(Math.random()*16777215).toString(16);
    }
    var connectedBlocks = [];
    var usedNumbers = [];
    var connectionId = 1;
    var connectedBlockFrom, connectedBlockTo, color;
    var alreadyConnected = function(type, identifier) {
        var ret = false;
        $.each(connectedBlocks, function(key, val) {
            if (val && val[type] == identifier) {
                ret = true;
                return;
            }
        });
        return ret;
    }
    var syncConnectBlock = function(type, $block) {
        if (type == 'from') {
            color = randomColor();
            if (connectedBlockFrom) {
                if (connectedBlockFrom == $block.data('template-identifier')) {
                    unsetConnectBlock('from', $block);
                }
            } else if (alreadyConnected('from', $block.data('template-identifier'))) {
                unsetConnectBlock('from', $block);
            } else {
                setConnectBlock('from', $block);
            }
        } else {
            if (connectedBlockFrom) {
                setConnectBlock('to', $block);
            } else if (connectedBlockTo || alreadyConnected('to', $block.data('template-identifier'))) {
                unsetConnectBlock('to', $block);
            }
        }
    }
    var setConnectBlock = function(type, $block) {
        var connectedId = getConnectionId();
        if (type == 'from') {
            connectedBlockFrom = $block.data('template-identifier');
            $block.find('.wrapper h5').prepend('<span class="connected-block" data-connected="' + connectedBlockFrom + '" style="background-color:' + color + '">' + connectedId + '</span> ');
        } else {
            connectedBlockTo = $block.data('template-identifier');
            if (connectedBlockFrom) {
                $block.find('.wrapper h5').prepend('<span class="connected-block" data-connected-from="' + connectedBlockFrom + '" style="background-color:' + color + '">' + connectedId + '</span> ');
                incConnectionId();
                connectedBlocks.push({
                    from: connectedBlockFrom,
                    to: connectedBlockTo,
                    id: connectedId
                });
                connectedBlockFrom = null;
                connectedBlockTo = null;
            }
        }
    }
    var unsetConnectBlock = function(type, $block) {
        var connectedTo, connectedFrom, connectedId;
        $.each(connectedBlocks, function(key, val) {
            if (val && val[type] == $block.data('template-identifier')) {
                connectedFrom = val['from'];
                connectedTo = val['to'];
                connectedId = val['id'];
                connectedBlocks.splice(key, 1);
                decConnectionId(connectedId);
            }
        });
        $block.find('.connected-block').last().remove();
        if (type == 'from') {
            $('[data-connected-from="' + connectedFrom + '"]').remove();
            connectedBlockFrom = null;
        } else {
            if (connectedFrom) {
                $('[data-connected="' + connectedFrom + '"]').remove();
            }
            connectedBlockTo = null;
        }
    }
    var getConnectionId = function() {
        while (existsConnectionId()) {
            connectionId++;
        }
        return connectionId;
    }
    var existsConnectionId = function() {
        var ret = false;
        $.each(usedNumbers, function(key, val) {
            if (val == connectionId) {
                ret = true;
                return;
            }
        });
        return ret;
    }
    var incConnectionId = function() {
        usedNumbers.push(connectionId);
    }
    var decConnectionId = function(delConnectionId) {
        $.each(usedNumbers, function(key, val) {
            if (val == delConnectionId) {
                usedNumbers.splice(key, 1);
            }
        });
        connectionId = 1;
    }
    var unsetAllConnectedBlocks = function() {
        connectedBlocks = [];
        connectedBlockFrom = null;
        connectedBlockTo = null;
        color = randomColor();
        usedNumbers = [];
        connectionId = 1;
        $('.connected-block').remove();
    }
    $('.must-connect').on('click', function() {
        syncConnectBlock('from', $(this))
    });
    $('.block-to-connect-new').on('click', function() {
        syncConnectBlock('to', $(this))
    });
    var isRedBlocksAreConnected = function() {
        var $tpl = $('.must-connect'),
            ret = true;
        if ($tpl.length) {
            $tpl.each(function() {
                var identifier = $(this).data('template-identifier');
                if (!alreadyConnected('from', identifier)) {
                    ret = false;
                    return;
                }
            });
        }
        return ret;
    }
    var showError = function(message) {
        var html = Mustache.to_html($("#errorMessageTemplate").html(), {
            message: message
        });
        $('#error-response').html(html);
    }
    $('#confirmSwitchTemplateAction #confirm').on('click', function() {
        $('#error-response').html("");
        var $this = $(this),
            selectedTpl = $('#templatesList').find(":selected:not([disabled])");
        if (selectedTpl.length != 1) {
            showError($this.data('missing-template'));
            return;
        }
        var templateId = selectedTpl.data('id');
        var url = $this.data('url');
        if (!isRedBlocksAreConnected()) {
            showError($this.data('missing-blocks'));
            return;
        }
        $.ajax({
            url: url,
            type: 'PUT',
            data: {
                _token: token,
                template_id: templateId,
                connected_blocks: JSON.stringify(connectedBlocks)
            }
        }).done(function(response) {
            if (!response.connectedBlocks) {
                showError($this.data('missing-blocks'));
            } else if (!response.template) {
                showError($this.data('missing-template'));
            } else {
                window.location.replace(response.url);
            }
        });
    });
    // Homepage
    $('.homepage-switch').on('ifChecked', function(event) {
        var url = $(this).data('url');
        $.ajax({
            method: "PUT",
            url: url,
            dataType: 'JSON',
            data: {
                _token: token,
            }
        });
    });
});
