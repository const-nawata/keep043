<?php
class Items extends CI_Model{
	private $tbl1	= 'items';

// 	var $id		= NULL;
	var $name	= '';
	var $date	= '';

	public function __construct(){
        parent::__construct();
    }
//______________________________________________________________________________

    public function insertItem( $data ){
    	$this->name	= $data['name'];
    	$this->date	= $data['date'];

    	$this->db->insert( $this->tbl1, $this);
    }
//______________________________________________________________________________

    public function getItems( $count='all' ){

    	$count	= $count=='all' ? NULL : $count;
    	$query	= $this->db->get( $this->tbl1, $count );

    	return $query->result();
    }

}//	Class end