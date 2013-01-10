<?php
require_once( $gl_pagesPath.'lists/tables/DepartmentsTable.php' );

class lists_Page extends multiTablesPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->mTablesList	= array(
			array(
				'table_code'	=> 'Departments',
				'menu_prompt'	=> _DEPARTMENTS
			)
		);
		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}
