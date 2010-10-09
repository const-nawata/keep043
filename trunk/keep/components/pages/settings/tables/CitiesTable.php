<?php
require_once( $gl_pagesPath."settings/panes/AddEditCityPane.php" );
class CitiesTable extends PTable{

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
		$this->mLevels			= array( _LEVEL_ADMIN );
		$this->mName			= "CitiesTableN";
		$this->mSourceDbTable	= 'cities_view';
		$this->mTargetDbTable	= 'cities';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 17;
		$this->mMaxGrPg			= 5;

		$this->mIsFixHeight		= true;

		$this->mColumns	= array(
		array(
	    		'field'		=> 'name',
	    		'name'		=> _CITY,
    			'ttl_css'	=> 'cities_viewTblCityTtlTd',
    			'sll_css'	=> 'cities_viewTblCityClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> true
		),
		array(
	    		'field'		=> 'country',
	    		'name'		=> _COUNTRY,
    			'ttl_css'	=> 'cities_viewTblCountryTtlTd',
    			'sll_css'	=> 'cities_viewTblCountryClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> true
		)
		);

		$this->setSearchFields( array( 'name', 'country' ) );
		$this->mPaneClassName	= 'AddEditCityPane';
	}
//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
//--------------------------------------------------------------------------------------------------

}//	Class end
?>