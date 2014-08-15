/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
(function() {
/*
var o = { exec: function(p) {
url = baseUrl + "/GetSomeData";
$.post(url, function(response) {
alert(response)
});
}
};
*/
CKEDITOR.plugins.add('newplugin', {
init: function(editor) {

editor.addCommand('newplugin',new CKEDITOR.dialogCommand('newplugin'));
editor.ui.addButton('newplugin', {
label: 'newplugin',
icon: '',
command: 'newplugin'
});
CKEDITOR.dialog.add('newplugin', this.path + 'dialogs/link.js');

/*
if (editor.addMenuItems)
editor.addMenuItem("newplugin", {
label: 'New Plugin',
command: 'newplugin',
group: 'clipboard', order: 9
});
if (editor.contextMenu)
editor.contextMenu.addListener(function() {
return { "newplugin": CKEDITOR.TRISTATE_OFF };
});

*/
}
});
})();
