<?php
require_once( $gl_pagesPath.'lists/tables/DepartmentsTable.php' );
require_once( $gl_pagesPath.'stock/DepsListController.php' );

// class stock_Page extends KeepPage{
class stock_Page extends SeveralTablesPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$list_contr	= new DepsListController( $this );
		$this-> __set( 'mListContr', $list_contr );

		$deps_obj	= new DepartmentsTable( NULL, FALSE, FALSE );
		$deps_obj->readDataForPage();
		$deps	= $deps_obj->__get( 'mInfo' );
		$deps	= $deps['recs'];

		$tbl_list	= array();
		foreach( $deps as $dep ){
			$tbl_list[]	= array( 'table_code' => $dep['id'], 'menu_prompt' => $dep['name'] );
		}
		$this->__set( 'mTablesList', $tbl_list );

		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

// 	public function initHtmlView( $view = NULL ){
// 	}
//______________________________________________________________________________


}//		Class end
