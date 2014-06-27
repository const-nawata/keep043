<?php

class Kpp extends CI_Controller{

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
		$this->load->view('main_page', $data );


// echo "ret:<br />";
// print_r($ret);

	}
//------------------------------------------------------------------------------

	public function add( $name=NULL, $date=NULL ){

		if($name==NULL || $date==NULL){
			$this->index();
			return FALSE;
		}

		$this->load->model('items');

		$data	= [
			'name'	=> $name,
			'date'	=> date('Y-m-d', strtotime($date)),
		];

		$this->items->insert_item( $data );

		$this->index();
	}
//------------------------------------------------------------------------------




}//	Class end
