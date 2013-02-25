<?php
require_once( $gl_pagesPath.'settings/panes/AddEditUnitPane.php' );
class UnitsTable extends PTable{

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
		$this->mLevels	= array( 'admin' );
		$this->mSourceDbTable	= 'units';
		$this->mSelectorColor	= '#EDD3EA';
		$this->mPgLen			= 16;
		$this->mMaxGrPg			= 5;
		$this->mUpperLine		= '<tr><td colspan="2" class="unitsTblUpperSellTtlTd">'._PNAME1.'</td></tr>';

		$this->mIsFixHeight		= TRUE;

		$this->mColumns	= array(
			array(
		    		'field'		=> 'full_name',
		    		'name'		=> _PFULL1,
	    			'ttl_css'	=> 'unitsTblFullTtlTd',
	    			'sll_css'	=> 'unitsTblFullClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> TRUE
			),
			array(
		    		'field'		=> 'brief_name',
		    		'name'		=> _PBRIEF1,
	    			'ttl_css'	=> 'unitsTblBreifTtlTd',
	    			'sll_css'	=> 'unitsTblBreifClTd',
	    			'bg_clr'	=> '#FFF7E2',
		    		'is_sort'	=> TRUE
			)
		);

		$this->__set( 'mSearchParams', array( 'fields'=>array( 'full_name', 'brief_name' )));
		$this->mPaneClassName	= 'AddEditUnitPane';
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//		Class end
