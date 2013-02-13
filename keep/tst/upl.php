<?php
/**
 * Upload files testing.
 */


$page_contet	=
'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />'.
'<html xmlns="http://www.w3.org/1999/xhtml" />'.
'<head>'.
'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />'.
'<title>Test</title>'.

'<link rel="stylesheet" href="tst.css" type="text/css" />'.
// '<link REL="SHORTCUT ICON" HREF="../icons/ic006.ico">'.

'<script type="text/javascript" src="jquery-1.4.2.min.js"></script>'.
'<script type="text/javascript" src="vendor/jquery.ui.widget.js"></script>'.
// '<script type="text/javascript" src="jquery.iframe-transport.js"></script>'.
'<script type="text/javascript" src="jquery.fileupload.js"></script>'.
'</head>'.

'<body id="body_id">'.


// ".IaUtilsHtmlBlock::createButton( "orange", "<button type='button' class='button'  title='"._UPLOAD."' id='logo_upload' name='logo_upload' >"._UPLOAD."</button>" )."


"<button type='button' id='logo_upload' name='logo_upload'>UPLOAD</button>".
"<input id='orgUploadField' type='file' name='file'>".
"<input type='hidden' name='file_name_inp' value='newfile'/>".
"<input id='LOGO' name='LOGO' type='hidden' value='' />";








print_r( $_SERVER['SCRIPT_URL'] );

$page_contet	.=
'</body>'.


"\n".'<script>'."\n\n".

'showFileName = function(){'."\n".
	"document.getElementById('file_name_inp').value = document.getElementById('file').value;\n".
	"document.getElementById('buttonUploadTdId').innerHTML = \"<div id='button' class='gneral_button button_25_inside button_orange'><div class='limit_90'></div><button name='logo_upload' id='logo_upload' title='' class='button' type='submit'>"._UPLOAD."</button></div>\";\n".
'};'."\n".

'function checkUploadForm(){'."\n".
	"return xajax.upload('uploadLogo', 'OrganizationForm');\n".
'}'.

"

	        $ (public static function () {
			});

	        ".
'</script>'.


/*
 *
 *
 *
 *
 *


			    $('#orgUploadField').fileupload({
			        dataType: 'json',
			        url: './?xjxfun=uploadLogo',
			        done: public static function (e, data) {
			        	if(data.result.error!=1){
			           		$('#logoImageDiv').css('background-image','url(\"./'+data.result.name+'\")');
			           	}else{
			           		alert('ggg');
						}
			        }
			    });






*/
'</html>'.
'';

echo $page_contet;
