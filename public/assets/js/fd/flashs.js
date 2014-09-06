/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert, external, confirm*/
'use strict';

$(document).ready(function () {
    $("#flash_date").datepicker({'format':'yyyy-mm-dd'});
    $("#start_time").timepicker({timeFormat: 'HH:mm:ss', interval : 15});
});
