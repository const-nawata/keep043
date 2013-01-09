<?php
require_once( $gl_pagesPath.'clients/AddEditClientPane.php' );

class ClientsTable extends PTable{

	public function __construct( $Owner=NULL, $isHndl=FALSE, $isInitView=TRUE ){
		$this->setProperties();
		parent::__construct( $Owner );
		if( $isInitView ){ $this->initHtmlView( $isHndl ); }
	}
//______________________________________________________________________________

	/**
	 * sets properties of table in constructor
	 * @return void
	 */
	private function setProperties(){
		$this->mLevels			= array( 'manager' );
		$this->mSourceDbTable	= 'clients_view';
		$this->mTargetDbTable	= 'users';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 17;
		$this->mMaxGrPg			= 10;

		$this->mIsFixHeight		= TRUE;

		$this->mColumns	= array(
			array(
		    		'field'		=> 'surname',
		    		'name'		=> _USER_SURNAME,
	    			'ttl_css'	=> 'managersTblSurnameTtlTd',
	    			'sll_css'	=> 'managersTblSurnameClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> TRUE
			),

			array(
		    		'field'		=> 'firstname',
		    		'name'		=> _USER_NAME,
	    			'ttl_css'	=> 'managersTblFirstNameTtlTd',
	    			'sll_css'	=> 'managersTblFirstnameClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> TRUE
			),

			array(
		    		'field'		=> 'city_country',
		    		'name'		=> _CITY.', '._COUNTRY,
	    			'ttl_css'	=> 'managersTblCityCountryTtlTd',
	    			'sll_css'	=> 'managersTblCityCountryClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> FALSE
			),

			array(
		    		'field'		=> 'email',
		    		'name'		=> _EMAIL_ADDR,
	    			'ttl_css'	=> 'managersTblEmailTtlTd',
	    			'sll_css'	=> 'managersTblEmailClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> FALSE
			),

			array(
		    		'field'		=> 'info',
		    		'name'		=> _INFO,
	    			'ttl_css'	=> 'managersTblInfoTtlTd',
	    			'sll_css'	=> 'managersTblInfoClTd',
	    			'bg_clr'	=> '#FFF7E2',
	    			'is_sort'	=> FALSE
			)
		);

		$this->setSearchFields( array( 'surname', 'firstname' ) );
		$this->mPaneClassName	= 'AddEditClientPane';
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
