<?php
require_once( $gl_pagesPath."managers/ManagersTable.php" );
class managers_Page extends PPage {

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	public function initHtmlView( $view = NULL ){
		if( $view == NULL ){
			$mng_tbl_obj	= new ManagersTable();

			$view	=
"<table cellpadding='0' cellspacing='0'>
	<tr>
		<td class='Page_Managers_SearchSellTd'>".$mng_tbl_obj->mSearchInputObj->getHtmlView()."</td>
		<td class='Page_Managers_ToolSellTd'>".$mng_tbl_obj->mToolPaneObj->getHtmlView()."</td>
	</tr>

	<tr><td colspan='2' style='background-color: #aaffaa;'>".$mng_tbl_obj->getHtmlView()."</td></tr>
</table>";
		}
		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

}
