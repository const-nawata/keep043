<?php
class tableListController  extends Core{

	public function __construct( $Owner = NULL ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	private function resetJsHandlers( &$objResponse, $info ){
		$objResponse->assign( 'prev_sell_code', 'value', $info[ 'new_code' ] );

		$handlers	= settings_Page::getNotSelectedSellHanlders( $info[ 'old_code' ], $info[ 'old_prompt' ] );
		$div_view	= settings_Page::getSellHtmlContent( $info[ 'old_code' ], $info[ 'old_prompt' ], $handlers, 'SeveralTablesPageMenuSellTd' );
		$objResponse->assign( 'td_sell_'. $info[ 'old_code' ], 'innerHTML', $div_view );

		$handlers	= array();
		$div_view	= settings_Page::getSellHtmlContent( $info[ 'new_code' ], $info[ 'new_prompt' ], $handlers, 'SeveralTablesPageMenuSelectedSellTd' );
		$objResponse->assign( 'td_sell_'. $info[ 'new_code' ], 'innerHTML', $div_view );
	}
//______________________________________________________________________________

	protected function getHtmlContent( $tblObj, $codeName ){
		return
'<table cellpadding="0" cellspacing="0">'.
	'<tr>'.
		'<td class="Page_Multi_Tables_'.$codeName.'_SearchSellTd">'.$tblObj->mSearchInputObj->getHtmlView().'</td>'.
		'<td class="Page_Multi_Tables_ToolSellTd">'.$tblObj->mToolPaneObj->getHtmlView().'</td>'.
	'</tr>'.

	'<tr><td colspan="2">'.$tblObj->getHtmlView().'</td></tr>'.
'</table>';
	}
//______________________________________________________________________________

	public function buildSelectedTableHtmlContent( $codeName ){
		$cls_name	= $codeName.'Table';
		$tbl_obj	= new $cls_name( NULL, FALSE );
		return $this->getHtmlContent( $tbl_obj, $codeName );
	}
//______________________________________________________________________________

	public function showTable( &$objResponse, $info ){
		$this->resetJsHandlers( $objResponse, $info );
		$table_view	= $this->buildSelectedTableHtmlContent( $info[ 'new_code' ] );
		$objResponse->assign( 'multi_tables_page_container', 'innerHTML', $table_view );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

}//	Class end
