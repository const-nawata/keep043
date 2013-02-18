<?php
// error_reporting(E_ALL | E_STRICT);



//TODO: Move all upload artefacts to ppsk library.

require('../libs/ppsk/Log.php');
require('UploadHandler.php');

class PpskUploadHandler extends UploadHandler{
	function __construct( $options = null, $initialize = true ){
		parent::__construct($options, $initialize);
	}
//______________________________________________________________________________

	protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index=NULL, $content_range=NULL ){
		$file	= parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range );

		if( isset( $file->error )){
			$file->name	= 'dummy.jpg';
			$file_path = $this->get_upload_path( $file->name );
			$file_size = $this->get_file_size( $file_path, $append_file );
			$file->url = $file_path;
			$file->thumbnail_url = $this->get_download_url( $file->name, 'thumbnail' );
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
		'thumbnail' => array(
			'max_width'	=> 200,
			'max_height'=> 110
		)
	)
);

$upload_handler = new PpskUploadHandler( $opts );

if( $_POST['fname'] !== 'dummy.jpg' ){
	$_GET['file']	= $_POST['main_url'];
	$upload_handler->delete( FALSE );
}
