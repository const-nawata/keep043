<?php
require_once( $gl_pagesPath."lists/panes/AddEditDepartmentPane.php" );

class DepartmentsTable extends PTable{

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
		$this->mLevels	= array( 'manager' );
		$this->mSourceDbTable	= 'departments_'.$_SESSION[ 'user_id' ];
		$this->mTargetDbTable	= 'departments';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 17;
		$this->mMaxGrPg			= 5;

		$this->mIsFixHeight		= true;

		$this->mColumns	= array(
			array(
		    		'field'		=> 'name',
		    		'name'		=> _PNAME1,
	    			'ttl_css'	=> 'departmentsTblNameTtlTd',
	    			'sll_css'	=> 'departmentsTblNameClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> true
			),
			array(
		    		'field'		=> 'info',
		    		'name'		=> _INFO,
	    			'ttl_css'	=> 'departmentsTblInfoTtlTd',
	    			'sll_css'	=> 'departmentsTblInfoClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> false
			)
		);

		$this->setSearchFields( array( 'name' ) );
		$this->mPaneClassName	= 'AddEditDepartmentPane';
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>