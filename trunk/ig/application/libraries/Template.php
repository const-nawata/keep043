<?php
if( !defined( 'BASEPATH' )) exit( 'No direct script access allowed' );

class Template{
	var $ci;

	public function __construct(){
		$this->ci	= &get_instance();
	}
//______________________________________________________________________________

	public function load( $tplView, $bodyView=NULL, $data=NULL){
		if ( !is_null( $bodyView )){
			if( file_exists( APPPATH.'views/'.$tplView.'/'.$bodyView )){
				$body_view_path = $tplView.'/'.$bodyView;
			}elseif( file_exists( APPPATH.'views/'.$tplView.'/'.$bodyView.'.php' )){
				$body_view_path = $tplView.'/'.$bodyView.'.php';
			}elseif( file_exists( APPPATH.'views/'.$bodyView )){
				$body_view_path = $bodyView;
			}elseif( file_exists( APPPATH.'views/'.$bodyView.'.php' )){
				$body_view_path = $bodyView.'.php';
			}else{
				show_error('Unable to load the requested file: '.$tpl_name.'/'.$view_name.'.php');
			}

			$body = $this->ci->load->view( $body_view_path, $data, TRUE );

			if ( is_null( $data )){
				$data = ['body' => $body];
			}elseif( is_array( $data )){
				$data['body'] = $body;
			}elseif( is_object( $data )){
				$data->body = $body;
			}
		}

		$this->ci->load->view( 'templates/'.$tplView, $data );
	}
//______________________________________________________________________________

}//	Class end
