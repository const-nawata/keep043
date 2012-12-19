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
		$class_name	= $_SESSION[ 'level' ].'_TabsSet';
		$tabs_obj	= new $class_name( $Owner );
		return $tabs_obj;
	}
	//--------------------------------------------------------------------------------------------------

	private function pageClassSelector( $Owner ){
		$class_name	= $_SESSION[ 'tab_code' ]._PAGE_POSTFIX;
		$page_obj	= new $class_name( $Owner );
		return $page_obj;
	}
	//--------------------------------------------------------------------------------------------------

	public function buildScreenByAjax( &$objResponse ){
		$html_view	= $this->getHtmlView();
		$objResponse->addAssign( 'tabs', 'innerHTML', $html_view[ 'tabs' ] );
		$objResponse->addAssign( 'wlcmUserLine', 'innerHTML', $html_view[ 'wlcm' ] );
		$objResponse->addAssign( 'mainContent', 'innerHTML', $html_view[ 'page' ] );
		$objResponse->addScript( _SET_JS_NULL );
		$objResponse->addScript( "current_tab_code='".$_SESSION[ 'tab_code' ]."'" );		//	This fackin script is necessary for Safari and Google Chrome
		$objResponse->addScript( $html_view[ 'js_code' ] );
	}
	//--------------------------------------------------------------------------------------------------

	public function initHtmlView(){
		$tabs_obj	= $this->tabsSetClassSelector( $this );
		$page_obj	= $this->pageClassSelector( $this );
		$view		= array(
        	'is_access'	=> true,
        	'tabs'		=> $tabs_obj->getHtmlView(),
        	'wlcm'		=> getWelcomeText(),
        	'page'		=> $page_obj->getHtmlView(),
        	'js_code'	=> $page_obj->getJsCode()
		);
		parent::initHtmlView( $view );
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>