CKEDITOR.dialog.add('fontawesomeDialog', function (editor) {
	return {
		title : editor.lang.fontawesome.editfa,
		resizable : CKEDITOR.DIALOG_RESIZE_BOTH,
		minWidth : 500,
		minHeight : 200,
		contents : [{
				id : 'info',
				elements : [{
						type : 'select',
						id : 'fontsize',
						label : editor.lang.fontawesome.fontsize,
						items : [['8', '8'], ['9', '9'], ['10', '10'], ['11', '11'], ['12', '12'], ['14', '14'], ['16', '16'], ['18', '18'], ['20', '20'], ['22', '22'], ['24', '24'], ['28', '28'], ['36', '36'], ['48', '48'], ['72', '72']],
						setup : function (element) {
							this.setValue(element.getStyle('font-size').replace('px', ''));
						},
						commit : function (element) {
							var style = this.getValue();
							if (style) {
								element.setStyle('font-size', style + 'px');
							} else {
								element.removeStyle('font-size');
							}
						}
					}, {
						type : 'text',
						id : 'facode',
						label : editor.lang.fontawesome.facode,
						setup : function (element) {
							this.setValue(element.getAttribute('class'));
						},
						commit : function (element) {
							var fa = this.getValue();
							if (fa) {
								element.setAttribute('class', fa);
							} else {
								element.removeAttribute('class');
							}
						}
					}, {
						id : 'fahtml',
						type : 'html',
						label : '',
						html : editor.lang.fontawesome.facode_tip
					}, {
						id : 'facolor',
						type : 'text',
						label : editor.lang.fontawesome.facolor,
						setup : function (element) {
							this.setValue(element.getStyle('color'));
						},
						commit : function (element) {
							var color1 = this.getValue();
							if (color1) {
								element.setStyle('color', color1);
							} else {
								element.removeStyle('color');
							}
						}
					}
				]
			}
		],
		buttons : [CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton],
		onCancel : function () {},
		onload : function () {},
		onOk : function () {
			var dialog = this;
			if (this.insertMode) {
				var fa = editor.document.createElement('i');
				fa.setAttribute('aria-hidden', 'true');
				fa.setAttribute('contenteditable', 'false');
				fa.setStyle('font-size', dialog.getValueOf('info', 'fontsize') + 'px'); // + 'px' is important
				fa.setStyle('color', dialog.getValueOf('info', 'facolor'));
				fa.setAttribute('class', dialog.getValueOf('info', 'facode'));
				editor.insertElement(fa);
			} else {
				var fa = this.element;
				this.commitContent(fa);
			}
		},
		onShow : function () {
			var selection = editor.getSelection();
			var element = selection.getStartElement();
			if (element)
				element = element.getAscendant('i', true);

			if (!element || element.getAttribute('class').indexOf('fa fa') === -1) {
				element = editor.document.createElement('i');
				this.insertMode = true;
			} else {
				this.insertMode = false;
			}

			this.element = element;
			if (!this.insertMode) {
				this.setupContent(this.element);
			}
		}
	};
});