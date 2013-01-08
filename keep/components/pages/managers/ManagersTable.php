<?php
require_once( $gl_pagesPath.'managers/AddEditManagerPane.php' );

class ManagersTable extends PTable{

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
		$this->mLevels			= array( 'admin' );
		$this->mSourceDbTable	= 'managers_view';
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
		$this->mPaneClassName	= 'AddEditManagerPane';
	}
//______________________________________________________________________________

	public function deleteRowHandler( &$objResponse, $nullValue ){
		$auth_obj = new Authentication();
		if( $auth_obj->isGrantAccess( $this->mLevels ) ){
			$class	= get_class( $this );
			$db_obj	= new KeepDbl( $this );
			$result	= $db_obj->deleteDbViewsForManager( $_SESSION[ 'tables' ][ $class ][ 'line_id' ] );
			if( $result[ 'is_error' ] ){
				$this->showAlertHandler( $objResponse, array( 'message' => $result[ 'description' ], 'focus' => $result[ 'focus_id' ] ) );
			}else{
				parent::deleteRowHandler( $objResponse, $nullValue );
			}
		}
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
