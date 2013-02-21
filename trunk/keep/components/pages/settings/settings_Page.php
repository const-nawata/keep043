<?php
require_once( $gl_pagesPath.'settings/tables/CitiesTable.php' );
require_once( $gl_pagesPath.'settings/tables/CountriesTable.php' );
require_once( $gl_pagesPath.'settings/tables/UnitsTable.php' );
require_once( $gl_pagesPath.'tableListController.php' );

class settings_Page extends SeveralTablesPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$list_contr	= new tableListController( $this );
		$this-> __set( 'mListContr', $list_contr );

		$this->mTablesList	= array(
			array( 'table_code' => 'Cities', 'menu_prompt' => _CITIES ),
			array( 'table_code'	=> 'Countries', 'menu_prompt' => _COUNTRIES	),
			array( 'table_code' => 'Units', 'menu_prompt' => _UNITS_NAMES )
		);

		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

}//	Class end
