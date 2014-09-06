/*jslint browser: true, nomen: true, unparam: true, node: true*/
/*global $, jQuery, document, alert, external, confirm*/
'use strict';

$(document).ready(function () {
    $("#start_date").datepicker({'format':'yyyy-mm-dd'});
    $("#end_date").datepicker({'format':'yyyy-mm-dd'});
});
