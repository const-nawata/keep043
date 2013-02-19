<?php
// error_reporting(E_ALL | E_STRICT);



//TODO: Move all upload artefacts to ppsk library.

require('../libs/ppsk/ppsk_constants.php');
require('../libs/ppsk/Log.php');
require('UploadHandler.php');

final class PpskUploadHandler extends UploadHandler{
	function __construct( $options = NULL, $initialize = TRUE ){
		parent::__construct($options, $initialize);
	}
//______________________________________________________________________________

	protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index=NULL, $content_range=NULL ){
		$file	= parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range );

		if( isset( $file->error )){
			$file->name			= _PPSK_DUMMY_IMG;
			$file->url			= $this->get_upload_path( $file->name );
			$file->thumbnail_url= $this->get_download_url( $file->name, _PPSK_THUMB_FOLD );
		}

		return $file;
	}
//______________________________________________________________________________

}//		Class end


$opts	= array(
// 	'inline_file_types' => '/\.(gif|jpe?g|png)$/i',
	'inline_file_types' => '/\.(jpe?g)$/i',
	'accept_file_types' => '/\.(jpe?g)$/i',

	'image_versions' => array(
		'thumbnail' => array(//TODO: Check if it is folder name.
			'max_width'	=> 200,
			'max_height'=> 110
		)
	)
);

$upload_handler = new PpskUploadHandler( $opts );

if( $_POST['fname'] !== _PPSK_DUMMY_IMG ){
	$_GET['file']	= $_POST['main_url'];
	$upload_handler->delete( FALSE );
}
