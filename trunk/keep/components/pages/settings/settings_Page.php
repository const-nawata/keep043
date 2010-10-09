<?php
require_once( $gl_pagesPath."settings/tables/CitiesTable.php" );
require_once( $gl_pagesPath."settings/tables/CountriesTable.php" );
require_once( $gl_pagesPath."settings/tables/UnitsTable.php" );

class settings_Page extends multiTablesPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->mTablesList	= array(
		array(
				'table_code'	=> 'Cities',
				'menu_prompt'	=> _CITIES
		),
		array(
				'table_code'	=> 'Countries',
				'menu_prompt'	=> _COUNTRIES
		),
		array(
				'table_code'	=> 'Units',
				'menu_prompt'	=> _UNITS_NAMES
		)
		);
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------
}//	Class end
?>