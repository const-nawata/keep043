<?php
class TableSearchField  extends Core{
	private $mFilterValue;
	private $mBtnSearch;
	private $mBtnClear;
//------------------//-----------------//-----------------//-------------------/

	public function __construct( $Owner, $filterValue = _EMPTY ){
		$this->mFilterValue	= $filterValue;
		$this->mName	= get_class( $Owner ).'_TableSearchField';
		parent::__construct( $Owner );
		$this->adjustProperties();
		$this->initHtmlView();
	}
//______________________________________________________________________________

	private function adjustProperties(){
		$button	= &$this->mBtnSearch;
		$button	= $this->mOwner->getFilterButtonParams( 'search' );
		$search_html_id = $button[ 'name' ] = 'btn_'.$this->mName.'_search';
		$button[ 'css_act' ]	= ( !isset( $button[ 'css_act' ] ) ) ? 'PPSK_searchBtnEnabled' : $button[ 'css_act' ];
		$search_css_dis = $button[ 'css_dis' ] = ( !isset( $button[ 'css_dis' ] ) ) ? 'PPSK_searchBtnDisabled' : $button[ 'css_dis' ];
		$button[ 'css_ovr' ]	= ( !isset( $button[ 'css_ovr' ] ) ) ? 'PPSK_searchBtnOver' : $button[ 'css_ovr' ];
		$button[ 'css_dwn' ]	= ( !isset( $button[ 'css_dwn' ] ) ) ? 'PPSK_searchBtnDown' : $button[ 'css_dwn' ];
		PPane::adjustBtnProperties( $button );

		$button	= &$this->mBtnClear;
		$button	= $this->mOwner->getFilterButtonParams( 'clear' );
		$clear_html_id = $button[ 'name' ]		= 'btn_'.$this->mName.'_clear';
		$button[ 'css_act' ]	= ( !isset( $button[ 'css_act' ] ) ) ? 'PPSK_clearSearchBtnEnabled' : $button[ 'css_act' ];
		$clear_css_dis = $button[ 'css_dis' ]	= ( !isset( $button[ 'css_dis' ] ) ) ? 'PPSK_clearSearchBtnDisabled' : $button[ 'css_dis' ];
		$button[ 'css_ovr' ]	= ( !isset( $button[ 'css_ovr' ] ) ) ? 'PPSK_clearSearchBtnOver' : $button[ 'css_ovr' ];
		$button[ 'css_dwn' ]	= ( !isset( $button[ 'css_dwn' ] ) ) ? 'PPSK_clearSearchBtnDown' : $button[ 'css_dwn' ];
		$button[ 'handlers' ][ 'onclick' ][ 'handler' ] .=
	    	"setElementDisabled( \"".$search_html_id."\", \"".$search_css_dis."\" );".
			"setElementDisabled( \"".$clear_html_id."\", \"".$clear_css_dis."\" );";

		PPane::adjustBtnProperties( $button );
	}
//______________________________________________________________________________

	public function initHtmlView( $view = '' ){
		$btn_search	= &$this->mBtnSearch;
		$btn_clear	= &$this->mBtnClear;

		if( _EMPTY == $this->mFilterValue ){
			$btn_clear[ 'is_dis' ]	= true;
			$btn_search[ 'is_dis' ]	= true;
		}

		$btn_search_obj	= new PaneButton( $this->mOwner, $btn_search );
		$btn_clear_obj	= new PaneButton( $this->mOwner, $btn_clear );

		$html_id	= "inp_".$this->mName;
		$search_html_id	= $btn_search[ 'name' ];
		$clear_html_id	= $btn_clear[ 'name' ];

		$onchange	=
"if( this.value != \"\" ){".
	"setElementEnabled( \"".$search_html_id."\", \"".$btn_search[ 'css_act' ]."\" );".
	"setElementEnabled( \"".$clear_html_id."\", \"".$btn_clear[ 'css_act' ]."\" );".
"}else{".
	"setElementDisabled( \"".$search_html_id."\", \"".$btn_search[ 'css_dis' ]."\" );".
	"setElementDisabled( \"".$clear_html_id."\", \"".$btn_clear[ 'css_dis' ]."\" );".
"}";

		$view	=
"<table class='PPSK_tableSearchFieldTbl' cellpadding='0' cellspacing='0'>
	<tr>
		<td>
			<input type='text' id='".$html_id."' name='".$html_id."' value='".$this->mFilterValue."' ".
				"onkeyup='".$onchange."' onchange='".$onchange."' />
		</td>

		<td class='PPSK_tableSearchFieldBtnTd'>".$btn_search_obj->getHtmlView()."</td>
		<td class='PPSK_tableSearchFieldBtnTd'>".$btn_clear_obj->getHtmlView()."</td>
	</tr>
</table>";
		parent:: initHtmlView( $view );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//		Class end
