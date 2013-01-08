<?php
require_once( $gl_pagesPath."clients/AddEditClientPane.php" );
class ClientsTable extends PTable{

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
		$this->mLevels			= array( 'manager' );
		$this->mSourceDbTable	= 'clients_'.$_SESSION[ 'user_id' ];
		$this->mTargetDbTable	= 'clients';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 17;
		$this->mMaxGrPg			= 10;

		$this->mIsFixHeight		= true;

		$this->mColumns	= array(
		array(
	    		'field'		=> 'surname',
	    		'name'		=> _USER_SURNAME,
    			'ttl_css'	=> 'managersTblSurnameTtlTd',
    			'sll_css'	=> 'managersTblSurnameClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> true
		),

		array(
	    		'field'		=> 'firstname',
	    		'name'		=> _USER_NAME,
    			'ttl_css'	=> 'managersTblFirstNameTtlTd',
    			'sll_css'	=> 'managersTblFirstnameClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> true
		),

		array(
	    		'field'		=> 'city_country',
	    		'name'		=> _CITY.", "._COUNTRY,
    			'ttl_css'	=> 'managersTblCityCountryTtlTd',
    			'sll_css'	=> 'managersTblCityCountryClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> false
		),

		array(
	    		'field'		=> 'email',
	    		'name'		=> _EMAIL_ADDR,
    			'ttl_css'	=> 'managersTblEmailTtlTd',
    			'sll_css'	=> 'managersTblEmailClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> false
		),

		array(
	    		'field'		=> 'info',
	    		'name'		=> _INFO,
    			'ttl_css'	=> 'managersTblInfoTtlTd',
    			'sll_css'	=> 'managersTblInfoClTd',
    			'bg_clr'	=> '#FFF7E2',
    			'is_sort'	=> false
		)
		);

		$this->setSearchFields( array( 'surname', 'firstname' ) );
		$this->mPaneClassName	= 'AddEditClientPane';
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>