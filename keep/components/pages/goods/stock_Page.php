<?php
class stock_Page extends PPage{

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

}
