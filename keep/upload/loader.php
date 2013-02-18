<?php
// error_reporting(E_ALL | E_STRICT);


require('../libs/ppsk/Log.php');
require('UploadHandler.php');

$opts	= array(
	'image_versions' => array(
		'thumbnail' => array(
			'max_width'	=> 200,
			'max_height'=> 110
		)
	)
);

$upload_handler = new UploadHandler( $opts );
$_GET['file']	= $_POST['main_url'];
$upload_handler->delete( FALSE );
