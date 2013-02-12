<?php
class AlertPaneRnd1 extends PRnd1Pane{
	private $mMessage	= '';

	public function __construct( $Owner = NULL, $message = '', $focus = '' ){
		$this->mTitle	=  _PPSK_ERROR;
		$this->mName	= 'AlertPaneRnd1';
		$this->mWidth	= 550;
		$this->mHeigth	= 200;

		$fcjs	= ( $focus != '' ) ? "setFocus(\"".$focus."\");" : '';

		$this->mButtons	= array(
		array(	//	Button to close pane
    			'name'		=> 'btn_cancel',
    			'prompt'	=> _PPSK_CLOSE,
    			'hint'		=> _PPSK_CLOSE,
    			'css_ovr'	=> 'btn_over',
    			'handlers'	=> array(
    				'onclick'	=> array(
    					'handler'	=> "removeElement(\"alert_container\");removeElement(\"alert_veil\");".$fcjs
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
//______________________________________________________________________________

/**
 * creates HTML content
 * @param	string $view - fictive parameter. This parameter is set for PHP strict compatibility
 * @return	void
 */
	public function initHtmlView( $view = '' ){
		$this->mContent =
'<div class="PPSK_alert_div">'.$this->mMessage.'</div>';

		parent::initHtmlView();
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
