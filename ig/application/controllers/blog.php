<?php
class Blog extends CI_Controller{

	public function index(){
		echo "Hello Dolly!!!";
	}
//------------------------------------------------------------------------------

	public function goods( $item=NULL, $id=NULL ){
		echo "id: $id";
	}
//------------------------------------------------------------------------------

}//	Class end
