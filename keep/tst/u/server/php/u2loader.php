<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

// error_reporting(E_ALL | E_STRICT);

require_once('Log.php');

require('UploadHandler.php');

$opts	= array(

	'image_versions' => array(
		'thumbnail' => array(
			'max_width' => 100,
			'max_height' => 40
		)
	)
);

$upload_handler = new UploadHandler( $opts );
