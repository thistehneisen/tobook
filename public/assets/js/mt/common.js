/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert, external, confirm*/
'use strict';

function onSetContent () {
    var content = $('#content').data('liveEdit').getXHTML();
    $("#content").val(content);
    return true;
}
