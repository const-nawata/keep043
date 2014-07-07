<?php
if( !defined( 'BASEPATH' )) exit( 'No direct script access allowed' );

class Auth extends CI_Controller{

	public function __construct(){
		parent::__construct();
		// Your own constructor code

		$this->output->enable_profiler( isset($this->input->get()['is_profile']));
	}
//------------------------------------------------------------------------------

	public function login(){

		$page_data = [
		    'title' => 'Login',
			'heading'=> 'Login Page'
			,'external_js'=> get_ext_js( $this )
		];

		$this->javascript->ready('');
		$this->javascript->compile('script_ready');

		$this->template->load( 'default', 'auth/login', $page_data );
	}
//------------------------------------------------------------------------------


}//	Class end
