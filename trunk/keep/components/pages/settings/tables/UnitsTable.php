<?php
require_once( $gl_pagesPath."settings/panes/AddEditUnitPane.php" );
class UnitsTable extends PTable{

	public function __construct( $Owner = NULL, $isHndl = false, $isInitView = true ){

		//		$auth_obj = new Authentication();
		//		if( $auth_obj->isGrantAccess( $this->mLevels ) ){

		$this->setProperties();
		parent::__construct( $Owner );
		if( $isInitView ){ $this->initHtmlView( $isHndl ); }

		//		}else{
		//			global $gl_Path;
		//			header('Location: access.php');
		//		}
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets properties of table in constructor
	 * @param	string $instName - uniq name for object instance.
	 * @return void
	 */
	private function setProperties(){
		$this->mLevels	= array( 'admin' );
		$this->mSourceDbTable	= 'units';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 16;
		$this->mMaxGrPg			= 5;
		$this->mUpperLine		= "<tr><td colspan='2' class='unitsTblUpperSellTtlTd'>"._PNAME1."</td></tr>";

		$this->mIsFixHeight		= true;

		$this->mColumns	= array(
		array(
	    		'field'		=> 'full_name',
	    		'name'		=> _PFULL1,
    			'ttl_css'	=> 'unitsTblFullTtlTd',
    			'sll_css'	=> 'unitsTblFullClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> true
		),
		array(
	    		'field'		=> 'brief_name',
	    		'name'		=> _PBRIEF1,
    			'ttl_css'	=> 'unitsTblBreifTtlTd',
    			'sll_css'	=> 'unitsTblBreifClTd',
    			'bg_clr'	=> '#FFF7E2',
	    		'is_sort'	=> true
		)
		);

		$this->setSearchFields( array( 'full_name', 'brief_name' ) );
		$this->mPaneClassName	= 'AddEditUnitPane';
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>