<?php
require_once( $gl_PpskPath.'pane/PaneButton.php' );
/**
 * See doc.txt before use this class.
 * PPane class is used as inheritable to create presentation of panel view.
 * You must create new class to use this class as parent and define properties of new class.
 * After new class creation you must create new object instance and  call buildViewHtmlContent method to get initial presentation of the form HTML content.

 Properties desription
 ---------------------------------
 protected $mForm = null	— form params if pane must be form.
 If pane is form then
 $mForm = array (
	 'event'=>'onsubmit or action',
	 'handler'=>'Onsubmit handler or URL'
 )

protected $mTitle = NULL; — Title for pane. Optional. You can override css properties by using ($this->mClassName).PPSK_innerBlockTitle class.

protected $mButtons = array ( — Array to create additional buttons. Optional.
	array (
		'name'	=>'Batton_name', - button name. This value is used as button id. Mandatory.
		'type'	=>'button', — type of button. Optional.
		'is_dis'=>false, 	— Status of button. (false = enabled, true = disabled) Optional.
		'prompt'=>'Button',	— Prompt name. Optional.
		'hint'	=>'Button',	— Hint name. Optional.
		'css_act'=>_EMPTY,	— CSS class for active state.
		'css_dis'=>_EMPTY,	— CSS class for desable state.
		'css_ovr'=>_EMPTY,	— CSS class for mouse over state.

		'handlers'=> array (	 - list of handlers of button. Optional.
			'onclick'=>array(
				[handler]=>'showUserPane();', — JS. Mandatory for handler
				[ask]=>'Are you sure?'	— Optional
			),
							...
		)
	),
 ...
);

 protected $mContent = '';
 protected $mWidth = 300;		— fraim width in pixels. Optional.
 protected $mHeigth = 300;		— fraim heigh in pixels. Optional.

 protected $mBkgClr = "transparend";		— background color.  Optional.
 protected $mBorderClr = _PPSK_BLACK_COLOR;	— border color.  Optional.


TODO: Provide opportunity to set standard close button (maybe replace one which is used now).
 */
class PPane extends Core{

/**
 * HTML content
 * @property	string	$mContent
 */
	protected $mContent		= '';
	protected $mJsScript	= '';	//	Script to execute after HTML creation
	protected $mForm		= NULL;
	protected $mTitle		= '';
	private $mButtons		= array();
	private $mHiddens		= array();
//----------------------//-----------------------//

	public function __get( $property ){
		return ( property_exists( 'PPane', $property ))
			? $this->$property
			: parent::__get( $property )  ;
	}
//______________________________________________________________________________

	public function __set( $property, $value=NULL ){
		if( property_exists( 'PPane', $property )){
			$this->$property = $value;
		}else{
			parent::__set( $property, $value );
		}
	}
//______________________________________________________________________________


	public function __construct( $Owner ){
		$this->adjustProperties();
		parent::__construct( $Owner );
	}
//--------------------------------------------------------------------------------------------------

	public static function adjustBtnProperties( &$button ){
		( !isset( $button['type'] ))		? $button['type']		= 'button'				:NULL;
		( !isset( $button['is_dis'] ))		? $button['is_dis']		= FALSE					:NULL;
		( !isset( $button['prompt'] ))		? $button['prompt']		= ''					:NULL;
		( !isset( $button['hint'] ) )		? $button[ 'hint']		= ''					:NULL;
		( !isset( $button['css_act'] ))		? $button['css_act']	= ''					:NULL;
		( !isset( $button['css_dis'] ))		? $button['css_dis']	= ''					:NULL;
		( !isset( $button['css_ovr'] ))		? $button['css_ovr']	= ''					:NULL;
		( !isset( $button['css_dwn'] ))		? $button['css_dwn']	= ''					:NULL;
		( !isset( $button['css_up'] ))		? $button['css_up']		= $button['css_act']	:NULL;
		( !isset( $button['handlers'] ))	? $button['handlers']	= array()				:NULL;

		$handlers	= &$button['handlers'];
		( !isset( $handlers['onclick'] )) ? $handlers[ 'onclick' ] = array( 'handler' => '' ) :'';

		( !isset( $handlers['onmousedown'] ))	? $handlers['onmousedown']	= array( 'handler'=> "mouseOverOut(this,\"".$button['css_dwn']."\");" ) :'';
		( !isset( $handlers['onmouseup'] ))		? $handlers['onmouseup']	= array( 'handler'=> "mouseOverOut(this,\"".$button['css_up']."\");" ) :'';
		( !isset( $handlers['onmouseover'] ))	? $handlers['onmouseover']	= array( 'handler'=> "mouseOverOut(this,\"".$button['css_ovr']."\");" ) :'';
		( !isset( $handlers['onmouseout'] ))	? $handlers['onmouseout']	= array( 'handler'=> "mouseOverOut(this,\"".$button['css_act']."\");" ) :'';
	}
//--------------------------------------------------------------------------------------------------

	private function adjustProperties(){
		foreach( $this->mButtons as &$button ){
			self::adjustBtnProperties( $button );
			$button['is_last']	= FALSE;
		}
		$button['is_last']	= TRUE;
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
		?
'<table cellpadding="0" cellspacing="0" class="PPSK_buttons_pane_tbl">'.
	'<tr><td class="PPSK_sell_before_btns_td">&nbsp;</td>'.$btns_view.'<td class="PPSK_sell_after_btns_td">&nbsp;</td></tr>'.
'</table>':'';
		return $content;
	}
//--------------------------------------------------------------------------------------------------

	/**
	 * gets
	 * @return string HTML content
	 */
	protected function getInnerHtmlContent(){
		//TODO: Describe CSS aditional class to change title style in doc

		$content	= ($this->mTitle != '') ? "<div  class='PPSK_innerBlockTitle ".$this->mName."_PaneTitle'>".$this->mTitle."</div>":'';
		$content	.= ($this->mForm != NULL)
		? "<form id='".$this->mName."Form' name='".$this->mName."Form' ".$this->mForm['event']."='".$this->mForm['handler']."'>":'';

		foreach( $this->mHiddens as $hdn_item ){
			$content	.= "<input type='hidden' name='".$hdn_item['id']."' id='".$hdn_item['id']."' value='".$hdn_item['value']."' />";
		}

		$content	.= $this->mContent;
		$content	.= $this->getButtonsView();
		$content	.= ($this->mForm != NULL) ? "</form>":'';
		return $content;
	}
//--------------------------------------------------------------------------------------------------

	/**
	 * sets HTML view
	 * @param	string $view - HTML string. If empty then default view is created.
	 * @return void
	 */
	public function initHtmlView( $view = '' ){
		$pane_view	= ( $view == '' )
			?
'<div class="PPSK_simple_pane" '.
	'style="'.
		'width:'.$this->mWidth.'px;'.
		'height:'.$this->mHeigth.'px;'.
		'background-color:'.$this->mBkgClr.';'.
		'border-color:'.$this->mBrdClr.';"'.
'>'.
	$this->getInnerHtmlContent().
'</div>'
			: $view;

    	parent:: initHtmlView( $pane_view );
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

}//		Class end
