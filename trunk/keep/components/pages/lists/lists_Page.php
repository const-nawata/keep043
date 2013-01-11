<?php
require_once( $gl_pagesPath.'lists/tables/DepartmentsTable.php' );
require_once( $gl_pagesPath.'lists/tables/GoodCatsTable.php' );

class lists_Page extends SeveralTablesPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->mTablesList	= array(
			array( 'table_code' => 'Departments', 'menu_prompt' => _DEPARTMENTS ),
			array( 'table_code' => 'GoodCats', 'menu_prompt' => _GOOD_CATS )
		);
		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}
