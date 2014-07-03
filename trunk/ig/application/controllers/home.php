<?php

class Home extends CI_Controller{

	public function __construct(){
		parent::__construct();
		// Your own constructor code

		$this->load->helper('url');
// print_r(  $_GET );

// 		$gett	= $this->input->get();
// 		$cond	= isset($gett['is_profile']);
		$this->output->enable_profiler( isset($this->input->get()['is_profile']));
	}
//------------------------------------------------------------------------------

	public function index(){

			$page_data	= [
				'title'	=> 'Home',
				'heading'	=> 'Home Page'
			];

			$this->load->view( 'header', $page_data );
			$this->load->view( 'home_page', $page_data );
			$this->load->view( 'footer' );

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

		$this->items->insertItem( $data );

		$this->items();
	}
//------------------------------------------------------------------------------

	public function items( $count = 'all' ){

		$this->load->model('items');

		$page_data	= [
			'title'	=> 'Items',
			'items'	=> $this->items->getItems( $count )
		];

		$this->load->view( 'header', $page_data );
		$this->load->view( 'items_page', $page_data );
		$this->load->view( 'footer' );
	}
//------------------------------------------------------------------------------




}//	Class end
