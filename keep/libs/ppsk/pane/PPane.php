<?php
require_once($gl_PpskPath."pane/PaneButton.php");
/**
 * See doc.txt before use this class.
 * PPane class is used as inheritable to create presentation of panel view.
 * You must create new class to use this class as parent and define properties of new class.
 * After new class creation you must create new object instance and  call buildViewHtmlContent method to get initial presentation of the form HTML content.

 Properties desription
 ---------------------------------
 protected $mForm = null	- form params if pane must be form.
 If pane is form then
 $mForm = array (
 'event'=>'onsubmit or action',
 'handler'=>'Onsubmit handler or URL'
 )

 protected $mTitle		= NULL;		- Title for pane. Optional. You can override css properties by using ($this->mClassName).PPSK_innerBlockTitle class.

 protected $mButtons		= array (	- Array to create additional buttons. Optional.
 array (
 'name'	=>'Batton_name', - button name. This value is used as button id. Mandatory.
 'type'	=>'button', - type of button. Optional.
 'is_dis'=>false, - Status of button. (false = enabled, true = disabled) Optional.
 'prompt'=>'Button',	- Prompt name. Optional.
 'hint'	=>'Button',	- Hint name. Optional.
 'css_act'=>_EMPTY,	- CSS class for active state.
 'css_dis'=>_EMPTY,	- CSS class for desable state.
 'css_ovr'=>_EMPTY,	- CSS class for mouse over state.
 'handlers'=> array (	 - list of handlers of button. Optional.
 'onclick'=>array(
 [handler]=>'alert',		- Mandatory
 [ask]=>'Are you shour'	- Optional
 ),
 ...
 );
 )
 ...
 );

 protected $mContent = '';
 protected $mWidth = 300;		- fraim width in pixels. Optional.
 protected $mHeigth = 300;		- fraim heigh in pixels. Optional.

 protected $mBkgClr = "transparend";			- background color.  Optional.
 protected $mBorderClr = _PPSK_BLACK_COLOR;	- border color.  Optional.

 --------------------
 General Remarks
 You can use
 --------------------
 This component was created and tested for  PHP version 5.2.11; Apache version 2.2.4.
 @author Constantine Nawata (nawataster@gmail.com)
 @version 1.0
 */
abstract class PPane extends Core{

/**
 * HTML content
 * @property	string	$mContent
 */
	protected $mContent		= _EMPTY;
	protected $mForm		= NULL;
	protected $mTitle		= NULL;
	protected $mButtons		= array();
	private $mHiddens		= array();
//----------------------//-----------------------//


	public function __construct( $Owner ){
		$this->adjustProperties();
		parent::__construct( $Owner );
	}
//--------------------------------------------------------------------------------------------------

	public function adjustBtnProperties( &$button ){
		( !isset( $button[ 'type' ] ) )		? $button[ 'type' ]		= 'button':'';
		( !isset( $button[ 'is_dis' ] ) )	? $button[ 'is_dis' ]	= false:'';
		( !isset( $button[ 'prompt' ] ) )	? $button[ 'prompt' ]	= _EMPTY:'';
		( !isset( $button[ 'hint' ] ) )		? $button[ 'hint' ]		= _EMPTY:'';
		( !isset( $button[ 'css_act' ] ) )	? $button[ 'css_act' ]	= _EMPTY:'';
		( !isset( $button[ 'css_dis' ] ) )	? $button[ 'css_dis' ]	= _EMPTY:'';
		( !isset( $button[ 'css_ovr' ] ) )	? $button[ 'css_ovr' ]	= _EMPTY:'';
		( !isset( $button[ 'css_dwn' ] ) )	? $button[ 'css_dwn' ]	= _EMPTY:'';
		( !isset( $button[ 'css_up' ] ) )	? $button[ 'css_up' ]	= $button[ 'css_act' ]:'';
		( !isset( $button[ 'handlers' ] ) )	? $button[ 'handlers' ]	= array():'';

		$handlers	= &$button[ 'handlers' ];
		( !isset( $handlers[ 'onclick' ] ) ) ? $handlers[ 'onclick' ] = array( 'handler' => _EMPTY ) :'';

		( !isset( $handlers[ 'onmousedown' ] ) )	? $handlers[ 'onmousedown' ]	= array( 'handler'=> "mouseOverOut( this, \"".$button[ 'css_dwn' ]."\");" ) :'';
		( !isset( $handlers[ 'onmouseup' ] ) )		? $handlers[ 'onmouseup' ]		= array( 'handler'=> "mouseOverOut( this, \"".$button[ 'css_up' ]."\");" ) :'';
		( !isset( $handlers[ 'onmouseover' ] ) )	? $handlers[ 'onmouseover' ]	= array( 'handler'=> "mouseOverOut( this, \"".$button[ 'css_ovr' ]."\");" ) :'';
		( !isset( $handlers[ 'onmouseout' ] ) )		? $handlers[ 'onmouseout' ]		= array( 'handler'=> "mouseOverOut( this, \"".$button[ 'css_act' ]."\");" ) :'';
	}
//--------------------------------------------------------------------------------------------------

	private function adjustProperties(){
		foreach( $this->mButtons as &$button ){
			self::adjustBtnProperties( $button );
			$button[ 'is_last' ]	= false;
		}
		$button[ 'is_last' ]	= true;
	}
//--------------------------------------------------------------------------------------------------

	/**
	 *
	 * @return string HTML content
	 */
	private function getButtonsView(){
		//TODO: Make cycle tabulation after last button. Must be go to first HTML element.
		$btns_view	= "";
		foreach( $this->mButtons as $btn_param ){
			$button_obj	= new PaneButton( $this->mOwner, $btn_param );
			$btns_view	.= "<td class='PPSK_button_sell_td' id='".$button_obj->mName."_btn_sell_td'>".$button_obj->getHtmlView()."</td>";
		}
		$content	= ( $btns_view != '' )
		? "
<table cellpadding='0' cellspacing='0'  class='PPSK_buttons_pane_tbl'>
	<tr><td class='PPSK_sell_before_btns_td'>&nbsp;</td>".$btns_view."<td class='PPSK_sell_after_btns_td'>&nbsp;</td></tr>
</table>":"";
		return $content;
	}
//--------------------------------------------------------------------------------------------------

	/**
	 *
	 * @return string HTML content
	 */
	protected function getInnerHtmlContent(){
		//TODO: Describe CSS aditional class to change title style in doc

		$content	= ($this->mTitle != NULL) ? "<div  class='PPSK_innerBlockTitle ".$this->mName."_PaneTitle'>".$this->mTitle."</div>":'';
		$content	.= ($this->mForm != NULL)
		? "<form id='".$this->mName."Form' name='".$this->mName."Form' ".$this->mForm['event']."='".$this->mForm['handler']."'>":'';

		foreach( $this->mHiddens as $hdn_item ){
			$content	.= "<input type='hidden' name='".$hdn_item[ 'id' ]."' id='".$hdn_item[ 'id' ]."' value='".$hdn_item[ 'value' ]."' />";
		}

		$content	.= $this->mContent;
		$content	.= $this->getButtonsView();
		$content	.= ($this->mForm != NULL) ? "</form>":'';
		return $content;
	}
//--------------------------------------------------------------------------------------------------

	/**
	 * sets HTML view
	 * @param uderfind $view
	 * @return void
	 */
	public function initHtmlView( $view = NULL ){
		$view	= ( $view == NULL )
		?	"<div class='PPSK_simple_pane' style='width: ".$this->mWidth."px; height: ".$this->mHeigth."px; background-color:".$this->mBkgClr."; border-color: ".$this->mBrdClr.";'>".
		$this->getInnerHtmlContent().
    			"</div>"
    			: $view;
    			parent:: initHtmlView( $view );
	}
//--------------------------------------------------------------------------------------------------


//##################################################################################################
//	Setters and getters

	public function setHiddenInput( $id, $value ){
		$this->mHiddens[]	= array( 'id' => $id, 'value' => $value );
	}
//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
//--------------------------------------------------------------------------------------------------

}// Class end
