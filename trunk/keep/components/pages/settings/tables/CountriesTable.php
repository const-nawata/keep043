<?php
require_once( $gl_pagesPath.'settings/panes/AddEditCountryPane.php' );
class CountriesTable extends PTable{

	public function __construct( $Owner = NULL, $isHndl = false, $isInitView = true ){
		$this->setProperties();
		parent::__construct( $Owner );
		if( $isInitView ){ $this->initHtmlView( $isHndl ); }
	}
//______________________________________________________________________________

	/**
	 * sets properties of table in constructor
	 * @param	string $instName - uniq name for object instance.
	 * @return void
	 */
	private function setProperties(){
		$this->mLevels	= array( 'admin' );
		$this->mName			= 'CountriesTableN';
		$this->mSourceDbTable	= 'countries';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 17;
		$this->mMaxGrPg			= 5;

		$this->mIsFixHeight		= TRUE;

		$this->mColumns	= array(
			array(
		    		'field'		=> 'name',
		    		'name'		=> _COUNTRY,
	    			'ttl_css'	=> 'countriesTblCountryTtlTd',
	    			'sll_css'	=> 'countriesTblCountryClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> TRUE
			)
		);

		$this->__set( 'mSearchParams', array( ['fields']=>array( 'name' )));
		$this->mPaneClassName	= 'AddEditCountryPane';
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//		Class end
