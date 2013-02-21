<?php
require_once( $gl_pagesPath.'tableListController.php' );
require_once( $gl_pagesPath.'stock/DepGoodsTable.php' );

class DepsListController extends tableListController{

	public function __construct( $Owner = NULL ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	public function buildSelectedTableHtmlContent( $depId ){
		$tbl_obj	= new DepGoodsTable( NULL, FALSE, FALSE );
		$tbl_obj->__set( 'mDepId', $depId );
		$tbl_obj->initHtmlView();
		return $this->getHtmlContent( $tbl_obj, 'DepGoodsTable' );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
