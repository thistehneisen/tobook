/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
(function() {
var o = { exec: function(p) {
url = baseUrl + "/GetSomeData";
$.post(url, function(response) {
alert(response)
});
}
};
CKEDITOR.plugins.add('footnote', {
init: function(editor) {
editor.addCommand('footnote', o);
editor.ui.addButton('footnote', {
label: 'footnote',
icon: this.path + 'footnote.png',
command: 'newplugin'
});
}
});
})();