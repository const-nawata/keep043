<?php
class Items extends CI_Model{
	private $tbl1	= 'items';

// 	var $id		= NULL;
	var $name	= '';
	var $date	= '';

	public function __construct(){
        parent::__construct();
    }
//------------------------------------------------------------------------------

    public function insert_item( $data ){
    	$this->name	= $data['name'];
    	$this->date	= $data['date'];

    	$this->db->insert( $this->tbl1, $this);
    }
//------------------------------------------------------------------------------

}//	Class end