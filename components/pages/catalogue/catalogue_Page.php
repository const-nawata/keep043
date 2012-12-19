<?php
require_once( $gl_pagesPath."catalogue/CataloguePane.php" );
class catalogue_Page extends PPage {

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

	public function initHtmlView() {
		$pain_obj	= new CataloguePane();



		$view	= $pain_obj->getHtmlView();
		parent::initHtmlView( $view );
	}
	//--------------------------------------------------------------------------------------------------

}
?>