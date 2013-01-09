<?php
/**
 */
class RunApp extends Core{

	public function __construct(){
		parent::__construct( $this );
		$this->initHtmlView();
	}
//______________________________________________________________________________

	private function tabsSetClassSelector( $Owner ){
		$class_name	= $_SESSION['level'].'_TabsSet';
		$tabs_obj	= new $class_name( $Owner );
		return $tabs_obj;
	}
//______________________________________________________________________________

	private function pageClassSelector( $Owner ){
		$class_name	= $_SESSION['tab_code'].'_Page';
		$page_obj	= new $class_name( $Owner );
		return $page_obj;
	}
//______________________________________________________________________________

	public function buildScreenByAjax( &$objResponse ){
		$html_view	= $this->getHtmlView();
		$objResponse->assign( 'tabs', 'innerHTML', $html_view['tabs'] );
		$objResponse->assign( 'wlcmUserLine', 'innerHTML', $html_view['wlcm'] );
		$objResponse->assign( 'mainContent', 'innerHTML', $html_view['page'] );
		$objResponse->script( _SET_JS_NULL );
		$objResponse->script( "current_tab_code='".$_SESSION['tab_code']."'" );		//	This fucking script is necessary for Safari and Google Chrome
		$objResponse->script( $html_view[ 'js_code' ] );
	}
//______________________________________________________________________________

	public function initHtmlView(){
		$tabs_obj	= $this->tabsSetClassSelector( $this );
		$page_obj	= $this->pageClassSelector( $this );
		$view		= array(
        	'is_access'	=> TRUE,
        	'tabs'		=> $tabs_obj->getHtmlView(),
        	'wlcm'		=> getWelcomeText(),
        	'page'		=> $page_obj->getHtmlView(),
        	'js_code'	=> $page_obj->getJsCode()
		);
		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
