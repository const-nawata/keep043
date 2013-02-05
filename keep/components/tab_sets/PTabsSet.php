<?php
require_once ( $gl_tab_setsPath.'TabImage.php' );
abstract class PTabsSet extends Core{
	/**
	 *
	 * @var array - list of tab codes
	 */
	protected $mTabCodes	= array();

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	private function setGeneralHandlers( &$TabIns ){
		$TabIns->setHandler( array( 'handler'=>"mouseOverOutTab(\"".$TabIns->mName."\", \"over\");" ), 'onmouseover' );
		$TabIns->setHandler( array( 'handler'=>"mouseOverOutTab(\"".$TabIns->mName."\", \"notSelected\");" ), 'onmouseout' );
	}
//______________________________________________________________________________

	/**
	 * sets tab params
	 * IMPORTANT!!! Before use this method $mTabCode property must be set in $TabIns parameter
	 * @param string $TabCode
	 * @return void
	 */
	private function setTabParams( &$TabIns ){
		$const_prmt	= '_TAB_PRMPT_'.$TabIns->mName;
		$tab_prompt	= constant( $const_prmt );
		$TabIns->setPrompt( $tab_prompt );
		$owner_class		= $this->getOwnerClassName();
		$onclick_handler	= $TabIns->mName.'_TabsHandler';
		$TabIns->unsetAllHandlers();
		if( $TabIns->mName != $_SESSION['tab_code'] ){
			$hndl_res	= self::getHandleResourceString( $onclick_handler, $owner_class );
			$TabIns->setHandler( array( 'handler'=>"xajax_onHandler(\"$hndl_res\");" ) );
			$this->setGeneralHandlers( $TabIns );
		}
	}
//______________________________________________________________________________

	/**
	 * builds view of set of tabs for page accoding to user's level and active tab.
	 * @access private
	 * @return string HTML content
	 * */
	private function buildTabsSetView(){
		$string	=
'<table cellspacing="0" cellpadding="0" border="0">'.
    '<tr>';
		$tab_image_obj	= new TabImage( $this );
		foreach ($this->mTabCodes as $tab_code) {
			$tab_image_obj->mName	= $tab_code;
			$this->setTabParams( $tab_image_obj );
			$string	.= '<td class="tabContainerTd">'.$tab_image_obj->getTabImage().'</td>';
		}
		$tab_image_obj	= NULL;
		$string	.=
'</tr>'.
'</table>';
		return $string;
	}
//______________________________________________________________________________

	/**
	 * sets HTML view
	 * @param uderfind $view
	 * @return void
	 */
	public function initHtmlView( $view = NULL ){
		$view	= ( $view == NULL ) ? $this->buildTabsSetView() : $view;
		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

	public function execTabHandler( &$objResponse ){
		$app	= new RunApp();
		$app->buildScreenByAjax( $objResponse );
	}
//______________________________________________________________________________

	public function logout_TabsHandler( &$objResponse, $TabCode ){
		$auth_obj	= new Authentication( true );
		$this->execTabHandler( $objResponse );
	}
//______________________________________________________________________________

}//	Class end
