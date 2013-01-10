<?php
class tableListController  extends Core{

	public function __construct( $Owner = NULL ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	private function resetJsHandlers( &$objResponse, $info ){
		$objResponse->assign( 'prev_sell_code', 'value', $info[ 'new_code' ] );

		$handlers	= settings_Page::getNotSelectedSellHanlders( $info[ 'old_code' ], $info[ 'old_prompt' ] );
		$div_view	= settings_Page::getSellHtmlContent( $info[ 'old_code' ], $info[ 'old_prompt' ], $handlers, 'mutiTablesPageMenuSellTd' );
		$objResponse->assign( 'td_sell_'. $info[ 'old_code' ], 'innerHTML', $div_view );

		$handlers	= array();
		$div_view	= settings_Page::getSellHtmlContent( $info[ 'new_code' ], $info[ 'new_prompt' ], $handlers, 'mutiTablesPageMenuSelectedSellTd' );
		$objResponse->assign( 'td_sell_'. $info[ 'new_code' ], 'innerHTML', $div_view );
	}
//______________________________________________________________________________

	public function buildSettingsTableHtmlContent( $codeName ){
		$cls_name	= $codeName.'Table';

		$tbl_obj	= new $cls_name( NULL, false );

		return
"<table cellpadding='0' cellspacing='0'>
	<tr>
		<td class='Page_Multi_Tables_".$codeName."_SearchSellTd'>".$tbl_obj->mSearchInputObj->getHtmlView()."</td>
		<td class='Page_Multi_Tables_ToolSellTd'>".$tbl_obj->mToolPaneObj->getHtmlView()."</td>
	</tr>

	<tr><td colspan='2'>".$tbl_obj->getHtmlView()."</td></tr>
</table>";
	}
//______________________________________________________________________________

	public function showTable( &$objResponse, $info ){
		$this->resetJsHandlers( $objResponse, $info );
		$table_view	= $this->buildSettingsTableHtmlContent( $info[ 'new_code' ] );
		$objResponse->assign( 'multi_tables_page_container', 'innerHTML', $table_view );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

}//	Class end
