<?php
require_once( $gl_pagesPath.'lists/tables/DepartmentsTable.php' );

// class stock_Page extends KeepPage{
class stock_Page extends SeveralTablesPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );

		$deps_obj	= new DepartmentsTable( NULL, FALSE, FALSE );
		$deps_obj->readDataForPage();
		$deps	= $deps_obj->__get( 'mInfo' );
		$deps	= $deps['recs'];


// Log::_log(print_r( $deps, TRUE));


		$tbl_list	= array();
		foreach( $deps as $dep ){
			$tbl_list[]	= array( 'table_code' => $dep['id'], 'menu_prompt' => $dep['name'] );
		}
		$this->__set( 'mTablesList', $tbl_list );

// 		$this->mTablesList	= array(
// 			array( 'table_code' => 'DepGoods', 'menu_prompt' => _GOODS )
// 		);
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
