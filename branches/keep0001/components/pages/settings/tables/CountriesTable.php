<?php
require_once( $gl_pagesPath."settings/panes/AddEditCountryPane.php" );
class CountriesTable extends PTable{

	public function __construct( $Owner = NULL, $isHndl = false, $isInitView = true ){
		$this->setProperties();
		parent::__construct( $Owner );
		if( $isInitView ){ $this->initHtmlView( $isHndl ); }
	}
//--------------------------------------------------------------------------------------------------

	/**
	 * sets properties of table in constructor
	 * @param	string $instName - uniq name for object instance.
	 * @return void
	 */
	private function setProperties(){
		$this->mLevels	= array( _LEVEL_ADMIN );
		//    	$this->mName			= "CountriesTableN";
		$this->mSourceDbTable	= 'countries';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 17;
		$this->mMaxGrPg			= 5;

		$this->mIsFixHeight		= true;

		$this->mColumns	= array(
		array(
	    		'field'		=> 'name',
	    		'name'		=> _COUNTRY,
    			'ttl_css'	=> 'countriesTblCountryTtlTd',
    			'sll_css'	=> 'countriesTblCountryClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> true
		)
		);

		$this->setSearchFields( array( 'name' ) );
		$this->mPaneClassName	= 'AddEditCountryPane';
	}
//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
//--------------------------------------------------------------------------------------------------

}//	Class end
?>