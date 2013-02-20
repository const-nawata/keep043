<?php
// class stock_Page extends SeveralTablesPage{
class stock_Page extends KeepPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

// 	public function initHtmlView( $view = NULL ){
// 	}
//______________________________________________________________________________

}//		Class end
