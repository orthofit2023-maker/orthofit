CKEDITOR.plugins.add('fontawesome', {
	lang : "en,zh-cn",
	icons : 'fontawesome',
	init : function (editor) {
		editor.addCommand('fontawesome', new CKEDITOR.dialogCommand('fontawesomeDialog'));

		editor.ui.addButton('Fontawesome', {
			label : editor.lang.fontawesome.addfa,
			command : 'fontawesome',
			toolbar : 'insert'
		});

		CKEDITOR.dialog.add('fontawesomeDialog', this.path + 'dialogs/fontawesome.js');

		if (editor.contextMenu) {
			editor.addMenuGroup('fontawesomeGroup');
			editor.addMenuItem('fontawesomeItem', {
				label : editor.lang.fontawesome.editfa,
				icon : this.path + 'icons/fontawesome.png',
				command : 'fontawesome',
				group : 'fontawesomeGroup'
			});

			editor.contextMenu.addListener(function (element) {
				if (element.getAscendant('i', true)) {
					return {
						fontawesomeItem : CKEDITOR.TRISTATE_OFF
					};
				}
			});
		}

	}
});