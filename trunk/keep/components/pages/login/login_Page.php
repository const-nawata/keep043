<?php
require_once( $gl_pagesPath."login/LoginPane.php" );
class login_Page extends PPage{

	public function __construct( $Owner ) {
		parent::__construct( $Owner );
		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function initHtmlView(){
		$login_pane	= new LoginPane( $this );
		$view		= $login_pane->getHtmlView();
		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
