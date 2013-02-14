<?php
// error_reporting(E_ALL | E_STRICT);


require('UploadHandler.php');

$opts	= array(

	'image_versions' => array(
		'thumbnail' => array(
			'max_width'	=> 60,
			'max_height'=> 60
		)
	)
);

$upload_handler = new UploadHandler( $opts );
