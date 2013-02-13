<?php
require_once( $gl_pagesPath.'catalogue/CataloguePane.php' );

class catalogue_Page extends PPage {

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	public function initHtmlView( $view = '' ) {
		$pain_obj	= new CataloguePane();

		$view	= $pain_obj->getHtmlView();
		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

}//		Class end
