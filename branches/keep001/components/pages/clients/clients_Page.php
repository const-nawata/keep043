<?php
require_once( $gl_pagesPath."clients/ClientsTable.php" );
class clients_Page extends PPage {
	public function __construct($Owner) {
		parent::__construct($Owner);
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function initHtmlView( $view = NULL ){
		if( $view == NULL ){
			$cl_tbl_obj	= new ClientsTable();

			$view	=
"<table cellpadding='0' cellspacing='0'>
	<tr>
		<td class='Page_Managers_SearchSellTd'>".$cl_tbl_obj->mSearchInputObj->getHtmlView()."</td>
		<td class='Page_Managers_ToolSellTd'>".$cl_tbl_obj->mToolPaneObj->getHtmlView()."</td>
	</tr>

	<tr><td colspan='2' style='background-color: #aaffaa;'>".$cl_tbl_obj->getHtmlView()."</td></tr>
</table>";
		}
		parent::initHtmlView( $view );
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------

}
?>