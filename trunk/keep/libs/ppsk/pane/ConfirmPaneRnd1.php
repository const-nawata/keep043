<?php
class ConfirmPaneRnd1 extends PRnd1Pane{
	private $mMessage	= _EMPTY;

	public function __construct( $Owner = NULL, $message = _EMPTY, $action = _EMPTY, $focus = _EMPTY ){
		$this->mTitle		=  _PPSK_ATTENTION;

		$this->mName	= 'AlertPaneRnd1';

		$this->mWidth		= 550;
		$this->mHeigth		= 200;

		$this->mButtons	= array(
		array(	//	Button to confirm
    			'name'		=> 'btn_confirm',
    			'prompt'	=> _PPSK_YES,
    			'hint'		=> _PPSK_YES,
    			'css_ovr'	=> 'btn_over',
    			'handlers'	=> array(
    				'onclick'	=> array(
    					'handler'	=>
    						"removeElement( \"alert_container\" );removeElement( \"alert_veil\" );".
    						"xajax_onHandler(\"".self::getHandleResourceString( $action, get_class( $Owner ) )."\", \"\");"
    						)
    						)
    						),
    						array(	//	Button to cancel pane
    			'name'		=> 'btn_cancel',
    			'prompt'	=> _PPSK_NO,
    			'hint'		=> _PPSK_NO,
    			'css_ovr'	=> 'btn_over',
    			'handlers'	=> array(
    				'onclick'	=> array(
    					'handler'	=> "removeElement( \"alert_container\" );removeElement( \"alert_veil\" );"
    					)
    					)
    					)
    					);
    					$this->mMessage	= $message;
    					$this->mBrdClr	= _PPSK_ALERT_BRD_COLOR;
    					$this->mBkgClr	= _PPSK_ALERT_BKG_COLOR;

    					parent::__construct( $Owner );
    					$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function initHtmlView(){
		$this->mContent =
"<div class='PPSK_alert_div'>".$this->mMessage."</div>";
		parent::initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function cancelInfo( &$objResponse, $focus ){
		$objResponse->remove( 'alert_container' );
		$objResponse->remove( 'alert_veil' );
		if( _EMPTY != $focus){
			$objResponse->script( "setFocus( '".$focus."' );" );
		}
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>