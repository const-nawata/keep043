<?php
// error_reporting(E_ALL | E_STRICT);


require('../libs/ppsk/Log.php');

// Log::_log("Poin3");



require('UploadHandler.php');

$opts	= array(
// 	'max_width'			=> 200,
// 	'max_height'		=> 110,
	'image_versions' => array(
		'thumbnail' => array(
			'max_width'	=> 200,
			'max_height'=> 110
		)
	)
);


$upload_handler = new UploadHandler( $opts );
$_GET['file']	= $_POST['prev_file'];
$upload_handler->delete( FALSE );
