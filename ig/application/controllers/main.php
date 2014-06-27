<?php
class Main extends CI_Controller{

	public function __construct(){
		parent::__construct();
		// Your own constructor code
	}
//------------------------------------------------------------------------------

	public function index(){

		$data	= [
			'title'	=> 'Main page',
			'heading'	=> 'Title of Main Page'
		];

// 		$ret	=
		$this->load->view('main_page', $data, TRUE );


// echo "ret:<br />";
// print_r($ret);

	}
//------------------------------------------------------------------------------

}//	Class end