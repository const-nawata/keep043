<?php
if( !defined( 'BASEPATH' )) exit( 'No direct script access allowed' );

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

		$page_data = [
		    'title' => 'Home',
			'heading'=> 'Home Page'
		];

		$this->template->load( 'default', 'home/index', $page_data );
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
			'date'	=> date('Y-m-d', strtotime( $date )),
		];

		$this->items->insertItem( $data );

		$this->items();
	}
//------------------------------------------------------------------------------

	public function items( $count = 'all' ){

		$this->load->model('items');

		$page_data	= [
			'title'	=> 'Items',
			'heading'=> 'Items Page',
			'items'	=> $this->items->getItems( $count )
		];

		$this->template->load( 'default', 'home/items', $page_data );
	}
//------------------------------------------------------------------------------




}//	Class end
