/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	//config.extraPlugins = 'ckeditor_fa';
	//config.contentsCss = [
	//	'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css'
	//];

	config.filebrowserUploadUrl = '/manage/ckupload.php';
	config.filebrowserBrowseUrl = '/ckfinder/ckfinder.html';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.forcePasteAsPlainText = true;
	config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Font,Form,Checkbox';
	config.contentsCss = '/css/style.css';
};
