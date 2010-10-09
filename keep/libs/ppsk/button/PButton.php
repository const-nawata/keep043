<?php
/**
 * See doc.txt before use this class.
 *
 * 	PButton class is used as inheritable class to create presentation of standard PPSK button.
 * 	You must create new class to use this class as parent and define properties of new class.
 * 	After new class creation you must create new object instance and then call initHtmlView
 * method to set initial presentation of HTML content.

 Properties desription
 ---------------------------------
 protected $mType	= 'button'; -	Type of button. If type is `submit` you must take into account where
 you are going to place onclick handler.
 protected $mCssAct	= _EMPTY;	- CSS class for active state
 protected $mCssDis	= _EMPTY;	- CSS class for disabled state
 protected $mCssOvr	= _EMPTY;	- CSS class for mouse over state
 private	$isDisabled	= false;	- initial state

 --------------------
 General Remarks
 --------------------
 Property Core::mName is used as button id.
 Default button style is used for enabled state.

 This component was created and tested for  PHP version 5.2.11; Apache version 2.2.4.
 @author Constantine Nawata (nawataster@gmail.com)
 @version 1.0
 */
abstract class PButton extends Core{
	//-----------------------//----------------------//----------------------//-----------------------//

	protected	$mType		= 'button';
	protected	$mCssAct	= _EMPTY;
	protected	$mCssDis	= _EMPTY;
	private		$isDisabled	= false;
	//-----------------------//----------------------//----------------------//-----------------------//

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
	//--------------------------------------------------------------------------------------------------  disabled=\"disabled\"

	/**
	 * sets HTML view
	 * @access public
	 * @param uderfind $view
	 * @return void
	 */
	public function initHtmlView( $view = NULL ){
		$view = ( $view == NULL )
		? "<button".$this->getHandlersHtml().
		( ( $this->isDisabled )
		? " class='".$this->mCssDis."' disabled "
		: " class='".$this->mCssAct."' " ).
			    		"id='".$this->mName."' style='white-space: nowrap;' type='".$this->mType."' title='".$this->getHint()."'>".
		$this->getPrompt().
	    			"</button>" : $view;
		parent::initHtmlView( $view );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets prompt
	 * @access public
	 * @param string $prompt prompt for item
	 * @return void
	 */
	public function setPrompt( $prompt ){
		parent::setPrompt( $prompt );
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets hint
	 * @access public
	 * @param string $hint hint for button
	 * @return void
	 */
	public function setHint( $hint ){
		parent::setHint( $hint );
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets status disabled
	 * @access public
	 * @return void
	 */
	public function setDisabled(){
		$this->isDisabled	= true;
		//		$this->unsetAllHandlers();
		//		$this->setHint( _EMPTY );
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets status disabled
	 * @access public
	 * @return void
	 */
	public function setEnabled(){
		$this->isDisabled	= false;
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end

?>