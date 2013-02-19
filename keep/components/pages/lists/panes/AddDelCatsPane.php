<?php
final class AddDelCatsPane extends PAddEditPane{

	public function __construct( $Owner ){
    	parent::__construct( $Owner );

		$this->mTitle	= _TITLE_ASSIGN_CATS;

		$this->mName	= 'AddDelCatsPaneN';

		$this->mTarCss	= 'edit_pane_txt_ar';
		$this->mInpCss	= 'edit_pane_input';
		$this->mSelCss	= 'edit_pane_sel';

		$this->mWidth	= 455;
		$this->mHeigth	= 400;

		$this->mBrdClr	= _PANE_BORDER_COLOR;
		$this->mBkgClr	= _GEN_BKGRND_COLOR;


// 		$this->mJsScript	= '';

// 		$this->mInitFocus= 'name';
	}
//______________________________________________________________________________   removeElement("pane_container");removeElement("veil");'

	protected function getEditPaneButtons(){
		$buttons= parent::getEditPaneButtons();

		$bnt	= &$buttons[1];
		$bnt['name']	= 'bnt_close';
		$bnt['prompt']	=
		$bnt['hint']	= _CLOSE;
		$bnt['handlers']['onclick']['handler']	= 'removeElement("cats_container");removeElement("cats_veil");';

		unset( $buttons[0] );

		return $buttons;
	}
//______________________________________________________________________________

// 	protected function getEditPaneButtons(){
// 		$buttons	= parent::getEditPaneButtons();
// 		$buttons[1]['handlers']['onclick']['handler']	= 'removeElement("pane_container");removeElement("veil");'.
// 								'xajax_onHandler("'.self::getHandleResourceString( 'delUpload', get_class($this)).'",null);';

// 		$buttons[2]	= $buttons[1];
// 		$buttons[1]	= $buttons[0];
// 		$buttons[0]	= array (	//	Button to show categories pane.
//     			'name'		=> 'btn_cats',
//     			'prompt'	=> _CATEGORIES,
//     			'hint'		=> _CATEGORIES,
//     			'css_ovr'	=>'btn_over',
// 				'handlers'	=> array(
// 					'onclick'	=> array(
// 						'handler'	=> 'xajax_onHandler("'.self::getHandleResourceString( 'addDelCatsHandler', get_class($this)).'",null);'
// 					)
// 				)
//     		);


// // 		$alert_obj	= new AlertPaneRnd1( $this, $info['message'], $info['focus'] );
// // 		$alert_mess	= $alert_obj->getHtmlView();
// // 		$objResponse->script( "prependDiv('body_id','alert_veil','PPSK_alert_vail_div');prependDiv('body_id','alert_container','PPSK_pane_alert_container_div');" );
// // 		$objResponse->assign( 'alert_container', 'innerHTML', $alert_mess );


// 		return $buttons;
// 	}
//______________________________________________________________________________

	public function initHtmlView( $view = '' ){
		global $gl_Path;

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	.= $this->getInputLineContent( 'name', 'text', _PNAME1._PPSK_ASTERISK, $old_info['name'], $tanindex++, self::_onchange );


		parent::initHtmlView();
	}
//______________________________________________________________________________

	protected function isValidData( &$formValues ){

		$formValues['is_valid']	= TRUE;
	}
//______________________________________________________________________________

	protected function adjustForm( $formValues ){
		$this->mSaveData	= array(

		);
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
