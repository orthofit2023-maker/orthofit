<div class="modal small fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
            <h3 id="myModalLabel">Delete Confirmation</h3>
        </div>
        <div class="modal-body">
            <p class="error-text"><i class="fa fa-warning modal-icon"></i>Are you sure you want to delete the record?<br>This cannot be undone.</p>
			<p class="debug-url"></p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
            <button class="btn btn-danger btn-ok" data-dismiss="modal" id="delete">Delete</button>
        </div>
      </div>
    </div>
</div>
<script>
$('#confirm-delete').on('show.bs.modal', function(e) {
	$(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
	document.frm.delid.value=$(e.relatedTarget).data('href');
	//$('.debug-url').html('Delete URL: <strong>' + $(this).find('.btn-ok').attr('href') + '</strong>');
});
$('#delete').on('click', function() {
	document.frm.method="post";
	document.frm.submit();
});
</script>

<footer>
<hr>
<p>&copy <?php echo date("Y")?> <a href="http://www.swarom.com" target="_blank"><?php echo $adminuser?></a></p>
</footer>
</div>
</div>
<script type="text/javascript">
// This is a check for the CKEditor class. If not defined, the paths must be checked.
if ( typeof CKEDITOR == 'undefined' )
{
	document.write(
		'<strong><span style="color: #ff0000">Error</span>: CKEditor not found</strong>.' +
		'This sample assumes that CKEditor (not included with CKFinder) is installed in' +
		'the "/ckeditor/" path. If you have it installed in a different place, just edit' +
		'this file, changing the wrong paths in the &lt;head&gt; (line 5) and the "BasePath"' +
		'value (line 32).' ) ;
}
else
{
	var editor = CKEDITOR.replace( 'editor1' ); var editor = CKEDITOR.replace( 'editor2' );
	//editor.setData( '<p>Just click the <b>Image</b> or <b>Link</b> button, and then <b>&quot;Browse Server&quot;</b>.</p>' );

	// Just call CKFinder.setupCKEditor and pass the CKEditor instance as the first argument.
	// The second parameter (optional), is the path for the CKFinder installation (default = "/ckfinder/").
	CKFinder.setupCKEditor( editor, '../' ) ;

	// It is also possible to pass an object with selected CKFinder properties as a second argument.
	// CKFinder.setupCKEditor( editor, { basePath : '../', skin : 'v1' } ) ;
}

</script>
<script type="text/javascript" src="js/chosen.jquery.js"></script>
<script src="lib/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="js/dropzone.js"></script>
<script type="text/javascript">
	$("[rel=tooltip]").tooltip();
	$(function() {
		$('.demo-cancel-click').click(function(){return false;});
	});

var config = {
  '.chosen'					 : { width: '100%' },
  '.chosen-select'           : { width: '100%' },
  '.chosen-select-deselect'  : { allow_single_deselect: true },
  '.chosen-select-no-single' : { disable_search_threshold: 10 },
  '.chosen-select-no-results': { no_results_text: 'Oops, nothing found!' },
  '.chosen-select-rtl'       : { rtl: true },
  '.chosen-select-width'     : { width: '100%' }
}
for (var selector in config) {
  $(selector).chosen(config[selector]);
}
</script>
<script type="text/javascript">
<!--
function CheckEmail(msg,ctr){ 

	var emailReg = "^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$";
	var regex = new RegExp(emailReg);
	var sError = ""
	var write_
	if (ctr.value.length == 0){
		write_ = msg + " can't be Blank"
		alert(write_)
		ctr.focus()
		return true   
	}else{
		if (ctr.value != "" && regex.test(ctr.value) == false){ 
			write_ = msg + " requires a valid value"
			ctr.focus()
			return true       
		}
		return false;
	}
}
//-->
</script>
</body></html>
