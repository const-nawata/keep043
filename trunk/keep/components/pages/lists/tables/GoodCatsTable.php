<?php
require_once( $gl_pagesPath.'lists/panes/AddEditGoodCatsPane.php' );

class GoodCatsTable extends PTable{

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
		$this->mTargetDbTable	= 'categories';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 17;
		$this->mMaxGrPg			= 5;

		$this->mIsFixHeight		= TRUE;

		$this->mColumns	= array(
			array(
		    		'field'		=> 'name',
		    		'name'		=> _PNAME1,
	    			'ttl_css'	=> 'good_catsTblNameTtlTd',
	    			'sll_css'	=> 'good_catsTblNameClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> TRUE
			)
		);

		$this->setSearchFields( array( 'name' ));
		$this->mPaneClassName	= 'AddEditGoodCatsPane';
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
