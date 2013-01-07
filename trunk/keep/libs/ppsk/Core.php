<?php
/**
 * Core class
 * @author Constantine Nawata <nawataster@gmail.com>
 *
 */
abstract class Core{
	const _stdWidth		= 300;
	const _stdHeight	= 300;
	//----------------------//----------------------//----------------------//----------------------//
	public $mName;

	private $mHtmlView;
	private $mPrmpt		= _EMPTY;
	private $mHint		= _EMPTY;
	protected $mBkgClr	= 'transparent';
	protected $mBrdClr	= _PPSK_BLACK_COLOR;
	protected $mWidth	= self::_stdWidth;
	protected $mHeigth	= self::_stdHeight;

	/**
	 * @property reference $mOwner contains reference to object instance in which handlers are held
	 * @access public
	 */
	public $mOwner;

	/**
	 * @property array $mHandlers contains list of handlers and its parameters
	 * 		[<event_name>]=>array(
	 * 			[handler]=> string which contains handler
	 * 			[ask]=> string which contains question if confirmation is necessary. If is not set then no confirmation question.
	 * 		)
	 * @access private
	 */
	private $mHandlers	= array();
	//----------------------//----------------------//----------------------//----------------------//

	/**
	 * Constructor
	 * @access public
	 * @param reference $Owner
	 * @return void
	 */
	public function __construct( $Owner = NULL ){
		$this->mOwner		= $Owner;
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets handler
	 * @access	protected
	 * @param	string $event
	 * @param	array $handler (
	 * 	[handler]=> string handler
	 * 	[ask]=>string to confirm
	 * )
	 *
	 * @return void
	 */
	protected function setHandler( $handler = _EMPTY, $event = 'onclick' ){
		$this->mHandlers[ $event ]	= $handler;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets prompt
	 * @access public
	 * @param string $prompt prompt for item
	 * @return void
	 */
	public function setPrompt( $prompt ){
		$this->mPrmpt	= trim( $prompt );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * gets prompt
	 * @access public
	 * @return string
	 */
	public function getPrompt(){
		return $this->mPrmpt;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets hint
	 * @access public
	 * @param string $hint hint for button
	 * @return void
	 */
	public function setHint( $hint ){
		$this->mHint	= trim( $hint );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * gets hint
	 * @access public
	 * @return string
	 */
	public function getHint(){
		return $this->mHint;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * unsets all handlers
	 * @access public
	 * @return void
	 */
	public function unsetAllHandlers(){
		$this->mHandlers	= array();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * shows message about access denied
	 * @access public
	 * @return object
	 */
	public function doAccessDenied(){
		session_unset();
		session_destroy();
		$objResponse = new xajaxResponse();
		$objResponse->script( "location.href =  '".$gl_PpskPath."access.php'" );
		return $objResponse;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets HTML view
	 * @access public
	 * @param uderfind $view
	 * @return void
	 */
	public function initHtmlView( $view ){
		$this->mHtmlView	= $view;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * gets HTML view
	 * @access public
	 * @param mixed $view
	 * @return void
	 */
	public function getHtmlView() {
		return $this->mHtmlView;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * gets owner class name
	 * @access public
	 * @return string
	 */
	public function getOwnerClassName(){
		return get_class( $this->mOwner );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * gets owner class name
	 * @access	private
	 * @return	string
	 */
	private function getFiller(){
		$start	= rand( 1, 20 );
		$end	= rand( ( $start + 1 ), ( $start + 10 ) );
		$filler	= session_id();
		return substr( $filler, $start, $end );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * forms resource string for ajax handler.
	 * @access	protected
	 * @param	string $hndlName
	 * @param	string $className
	 * @return	string
	 */
	protected function getHandleResourceString( $hndlName, $className ){
		$str	= $hndlName.":".$className;
		$filler	= self::getFiller();
		$str	= $filler.":".$str.":".$filler;

		if( _PPSK_IS_CIPHER ){
			$cipher_obj	= new sipherManager( $_SESSION[ 'cipher_base' ], $_SESSION[ 'cipher_key' ] );
			$str		= $cipher_obj->encipherString( $str );
		}
		return $str;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * forms enciphered id string
	 * @access	protected
	 * @param	integer $id
	 * @return	string
	 */
	protected function encipherFilledValue( $value ){
		$filler	= self::getFiller();
		$str	= $filler.":".$value;

		if( _PPSK_IS_CIPHER ){
			$cipher_obj	= new sipherManager( $_SESSION[ 'cipher_base' ], $_SESSION[ 'cipher_key' ] );
			$str		= $cipher_obj->encipherString( $str );
		}
		return $str;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * forms deciphered id string
	 * @access	protected
	 * @param	string $str
	 * @return	integer
	 */
	protected function decipherFilledValue( $str ){
		if( _PPSK_IS_CIPHER ){
			$cipher_obj	= new sipherManager( $_SESSION[ 'cipher_base' ], $_SESSION[ 'cipher_key' ] );
			$str		= $cipher_obj->decipherString( $str );
		}
		list( $filler, $value )	= explode( ':', $str );
		return $value;
	}
	//--------------------------------------------------------------------------------------------------

	//	Handlers	<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>

	/**
	 * gets handlers
	 * @access protected
	 * @return string HTML content
	 */
	protected function getHandlersHtml(){
		$string	= _EMPTY;
		foreach ( $this->mHandlers as $event => $handler ){
			$cnf = ( isset( $handler[ 'ask' ] ) ) ? "var cond = confirm(\"".$handler[ 'ask' ]."\"); if(cond)" : _EMPTY;
			( $handler[ 'handler' ] != _EMPTY ) ? $string	.= " ".$event."='".$cnf.$handler[ 'handler' ]."' ":'';
		}
		return $string;
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * shows pane with confirmation parameters
	 * @param object $objResponse
	 * @param array $params = array(
	 * 	string [message] 	=> ...
	 *  string [action]		=> ...
	 * )
	 * @return unknown_type
	 */
	public function showConfirmHandler( &$objResponse, $params ){
		$params[ 'action' ]	= self::decipherFilledValue( $params[ 'action' ] );
		$alert_obj	= new ConfirmPaneRnd1( $this, $params[ 'message' ], $params[ 'action' ] );
		$alert_mess	= $alert_obj->getHtmlView();
		$objResponse->script( "prependDiv( 'body_id', 'alert_veil', 'PPSK_alert_vail_div'); prependDiv( 'body_id', 'alert_container', 'PPSK_pane_alert_container_div');" );
		$objResponse->assign( 'alert_container', 'innerHTML', $alert_mess );
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * shows pane with alert message
	 * @param object $objResponse
	 * @param array $info = array(
	 * 	string [message] 	=> ...
	 *  string [focus]		=> id of HTML element which must be focused after pane closing.
	 * )
	 * @return unknown_type
	 */
	public function showAlertHandler( &$objResponse, $info ){
		$alert_obj	= new AlertPaneRnd1( $this, $info[ 'message' ], $info[ 'focus' ] );
		$alert_mess	= $alert_obj->getHtmlView();
		$objResponse->script( "prependDiv( 'body_id', 'alert_veil', 'PPSK_alert_vail_div'); prependDiv( 'body_id', 'alert_container', 'PPSK_pane_alert_container_div');" );
		$objResponse->assign( 'alert_container', 'innerHTML', $alert_mess );
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>