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
		return ( property_exists( 'DepGoodsTable', $property ))
			? $this->$property
			: parent::__get( $property )  ;
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

		$this->__set( 'mSearchParams', array( 'fields'=>array( 'name', 'cku' )));


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

		$this->__set( 'mSearchParams', array( 'fields'=>array( 'name', 'cku' )));
		$this->mPaneClassName	= 'StockOptsPane';
	}
//______________________________________________________________________________

	protected function getToolBtns(){
		$type	= 'stk_ops';

		return array(
			"$type"	=> array(
				'hint'	=> _STOCK_OPERATIONS,
    			'handlers'	=> array(
    				'onclick'	=> array(
    					'handler'	=> 'xajax_onHandler("'.self::getHandleResourceString( 'onShowStkOps', get_class( $this )).'",null);'
    				)
    			),
				'css_dis'	=> 'Keep_'.$type.'BtnDisabled',
				'css_act'	=> 'Keep_'.$type.'BtnEnabled',
				'css_ovr'	=> 'Keep_'.$type.'BtnOver',
				'css_dwn'	=> 'Keep_'.$type.'BtnDown'
    		)
    	);
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	protected function getCondition(){
		$table	= $this->mSourceDbTable;
		$join_cond	= 'LEFT JOIN `stock` ON `stock`.`good_id`=`'.$table.'`.`id`';
		$sql_cond	= parent::getCondition();
		return $join_cond.$sql_cond.' AND `stock`.`depatement_id`='.$this->mDepId;
	}
//______________________________________________________________________________

	public function onShowStkOps( &$objResponse, $dummy ){


		$objResponse->script( 'alert("onShowStkOps handler was call!!!");' );
// 		$this->setPAddEditPane( $objResponse, NULL );
	}
//______________________________________________________________________________

}//	Class end
