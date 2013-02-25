<?php
require_once( $gl_pagesPath.'lists/panes/AddEditDepartmentPane.php' );

class DepartmentsTable extends PTable{

	public function __construct( $Owner = NULL, $isHndl=FALSE, $isInitView=TRUE ){
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
		$this->mLevels	= array( 'manager' );
		$this->mSourceDbTable	=
		$this->mTargetDbTable	= 'departments';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 17;
		$this->mMaxGrPg			= 5;

		$this->mIsFixHeight		= TRUE;

		$this->mColumns	= array(
			array(
		    		'field'		=> 'name',
		    		'name'		=> _PNAME1,
	    			'ttl_css'	=> 'departmentsTblNameTtlTd',
	    			'sll_css'	=> 'departmentsTblNameClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> TRUE
			),
			array(
		    		'field'		=> 'info',
		    		'name'		=> _INFO,
	    			'ttl_css'	=> 'departmentsTblInfoTtlTd',
	    			'sll_css'	=> 'departmentsTblInfoClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> FALSE
			)
		);

		$this->__set( 'mSearchParams', array( 'fields'=>array( 'name' )));
		$this->mPaneClassName	= 'AddEditDepartmentPane';
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
