<?php
require_once( $gl_pagesPath.'managers/ManagersTable.php' );
class managers_Page extends KeepPage {

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
			$tbl_obj	= new ManagersTable();
			$tpane_obj	= $tbl_obj->__get('mToolPaneObj');

			$view	=
'<table cellpadding="0" cellspacing="0">'.
	'<tr>'.
		'<td class="Page_Managers_SearchSellTd">'.$tbl_obj->mSearchInputObj->getHtmlView().'</td>'.
		'<td class="Page_Managers_ToolSellTd">'.$tpane_obj->getHtmlView().'</td>'.
	'</tr>'.

	'<tr><td colspan="2" style="background-color:#aaffaa;">'.$tbl_obj->getHtmlView().'</td></tr>'.
'</table>';
		}
		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

}
