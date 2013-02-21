<?php
// require_once( $gl_pagesPath.'lists/panes/AddEditGoodsPane.php' );

final class DepGoodsTable extends PTable{
	private $mDepId;

	public function __construct( $Owner=NULL, $isHndl=FALSE, $isInitView=TRUE ){
		$this->setProperties();
		parent::__construct( $Owner );
		if( $isInitView ){ $this->initHtmlView( $isHndl ); }
	}
//______________________________________________________________________________

	public function __get( $property ){
		if( property_exists( 'DepGoodsTable', $property )){
			return $this->$property;
		}else{
			return parent::__get( $property )  ;
		}
	}
//______________________________________________________________________________

	public function __set( $property, $value=NULL ){
		if( property_exists( 'DepGoodsTable', $property )){
			$this->$property = $value;
		}else{
			parent::__set( $property, $value );
		}
	}
//______________________________________________________________________________

	/**
	 * sets properties of table in constructor
	 * @param	string $instName - uniq name for object instance.
	 * @return void
	 */
	private function setProperties(){
		$this->__set( 'mLevels', $depId );
		$this->__set( 'mSourceDbTable', 'goods' );
		$this->__set( 'mTargetDbTable', 'goods' );
		$this->__set( 'mSelectorColor', '#EDD3EA' );
		$this->__set( 'mPgLen', 17 );
		$this->__set( 'mMaxGrPg', 10 );
		$this->__set( 'mIsFixHeight', TRUE );

		$this->setSearchFields( array( 'name', 'cku' ));


		$this->mColumns	= array(
			array(
				'field'		=> 'name',
				'name'		=> _PNAME1,
				'ttl_css'	=> 'goodsTblNameTtlTd',
				'sll_css'	=> 'goodsTblNameClTd',
				'bg_clr'	=> '#FFF7E2',
				'is_sort'	=> TRUE
			),

			array(
				'field'		=> 'cku',
				'name'		=> _GOOD_ARTICLE,
				'ttl_css'	=> 'goodsTblArticleTtlTd',
				'sll_css'	=> 'goodsTblArticleClTd',
				'bg_clr'	=> '#FFF7E2',
				'is_sort'	=> TRUE
			),

			array(
				'field'		=> 'qnt_packs',
				'alias'		=> 'stock',
				'name'		=> _GOOD_IN_PACK,
				'ttl_css'	=> 'goodsTblArticleTtlTd',
				'sll_css'	=> 'goodsTblArticleClTd',
				'bg_clr'	=> '#FFF7E2'
			),

			array(
				'field'		=> 'qnt_assr',
				'alias'		=> 'stock',
				'name'		=> _GOOD_IN_ASSORT,
				'ttl_css'	=> 'goodsTblArticleTtlTd',
				'sll_css'	=> 'goodsTblArticleClTd',
				'bg_clr'	=> '#FFF7E2'
			)

		);

		$this->setSearchFields( array( 'name', 'cku' ));
		$this->mPaneClassName	= 'AddEditGoodsPane';
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	protected function getCondition(){
		$table	= $this->mSourceDbTable;

		$join_cond	=
'LEFT JOIN `stock` ON `stock`.`good_id`=`'.$table.'`.`id`'.
'';

		$sql_cond	= parent::getCondition();
		$sql_cond	= ($sql_cond != '') ? ' AND '.$sql_cond : '';

		$sql_cond	= $join_cond.' WHERE `stock`.`depatement_id`='.$this->mDepId.$sql_cond;

// Log::_log("$sql_cond");


		return $sql_cond;
	}
//______________________________________________________________________________

}//	Class end
