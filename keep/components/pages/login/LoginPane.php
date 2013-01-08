<?php
class LoginPane extends PRnd1Pane{

	public function __construct(){
		$this->mTitle		= _TITLE_INPUT_LOGIN;
		$this->mWidth	= 320;
		$this->mHeigth	= 200;
		$this->mBrdClr	= _PANE_BORDER_COLOR;
		$this->mBkgClr	= _GEN_BKGRND_COLOR;

		$this->mForm	= array(
    		'event'		=> 'onsubmit',
			'handler'	=> "xajax_onHandler( \"".$this->getHandleResourceString( 'doLogin', 'LoginPane' )."\",xajax.getFormValues(this));return false;"
    	);

		$this->mButtons	= array(

    		//###############################################################################
    		//	Example block for additional buttons	(Don't delete this block)
    		//    		array(
    		//				'name'		=>'btn1',
    		//    			'prompt'	=>'Button1',
    		//    			'hint'		=>'Button1',
    		////    			'is_dis'	=>true,
    		//    			'css_dis'	=>'btn_disabled',
    		////				'css_act'	=>'PPSK_tablePrevPageAct',
    		//    			'css_ovr'	=>'btn_over',
    		//    			'handlers'	=>array(
    		//    				'onclick'	=> array(
    		//    					'handler'	=> "xajax_onHandler(\"".$this->getHandleResourceString('TestBtn1', 'LoginPane')."\", \"Button 1 was clicked.\");"
    		//    				)/*,
    		//    				'onmouseover'=>array(
    		//    					'handler'=>"this.className=\"btn_over\""
    		//    				),
    		//    				'onmouseout'=>array(
    		//    					'handler'=>"this.className=\"PPSK_tablePrevPageAct\""
    		//    				)*/
    		//    			)
    		//    		),
    		//    		array(
    		//    			'name'		=> 'btn2',
    		//    			'prompt'	=> 'Button2',
    		//    			'hint'		=> 'Button2',
    		//    			'css_ovr'	=> 'btn_over',
    		//    			'handlers'	=> array(
    		//    				'onclick'	=> array(
    		//    					'handler'	=> "xajax_onHandler(\"".$this->getHandleResourceString('TestBtn2', 'LoginPane')."\", \"Button 2 was clicked.\");",
    		//    				)
    		//    			)
    		//    		),
    		//	Example end
    		//###############################################################################

			array (	//	Button to accept login information
				'name'=>'btn3',
				'type'=>'submit',
				'prompt'=>constant (_TAB_PRMPT._TAB_LOGIN_CODE),
				'hint'=>constant (_TAB_PRMPT._TAB_LOGIN_CODE),
				'css_ovr'	=>'btn_over'
			)
		);

    	$this->mContent	= $this->getLoginPaneHtmlContent();//	Individual content

    	parent::__construct( $this );
    	$this->initHtmlView();
	}
//______________________________________________________________________________

	/**
	 * gets individual content of login pane. This method is called in constructor.
	 * @access private
	 * @return string HTML content
	 */
	private function getLoginPaneHtmlContent(){
		return "
<table cellpadding='0' cellspacing='0'>
	<tr><td class='promptTD'>"._LOGIN."</td><td class='inputField'><input tabindex='1' type='text' id='login' name='login' value='' /></td></tr>
	<tr><td class='promptTD'>"._PASSWORD."</td><td class='inputField'><input tabindex='2' type='password' id='password' name='password' value='' /></td></tr>
	<tr><td>&nbsp;</td><td class='inputField'><a>"._LOGIN_FOGOTTEN."</a></td></tr>
</table>
<div id='errLoginCont' class='errorContainer'></div>";
	}
//______________________________________________________________________________

	/**
	 * checks login and password
	 * @param array $formValues -	(
	 * 			[login]=>string
	 * 			[password]=>string
	 * 		)
	 * @return false if login or password is wrong or
	 * array(
	 * 	['type']=>string user's level
	 * 	['id']=>integer DB record id
	 * )
	 */
	private function checkLogin( $formValues ){
		$db_obj	= new KeepDbl( $this );
// 		$sql = "SELECT `id`, `level` FROM `login_info` WHERE `login`='".$formValues['login']."' AND `password`='".$formValues['password']."'";
		$sql = "SELECT `id`, `level` FROM `users` WHERE `login`='".$formValues['login']."' AND `password`='".$formValues['password']."'";

		$user_info = $db_obj->execSelectQuery( $sql, 'LoginPane::checkLogin' );
		if( (bool)count( $user_info )){ return $user_info[0]; }
		else{ return FALSE; }
	}
//______________________________________________________________________________

	public function doLogin( &$objResponse, $formValues ){
		$login_info	= $this->checkLogin( $formValues );

		if( $login_info ){
			$_SESSION['level']		= $login_info['level'];
			$_SESSION['user_id']	= $login_info['id'];
			$_SESSION['tab_code']	= NULL;
			PTabsSet::execTabHandler( $objResponse );
		}else{
			$objResponse->assign( 'errLoginCont', 'innerHTML', _MESSAGE_BAD_LOGIN );
		}
	}
//______________________________________________________________________________

	//This block was left as example for buttons (see above)
	public function TestBtn1( &$objResponse, $Value ){
		$objResponse->assign('errLoginCont', 'innerHTML', $Value);
	}
//______________________________________________________________________________

	public function TestBtn2(&$objResponse, $Value){
		$objResponse->assign('errLoginCont', 'innerHTML', $Value);
	}
//______________________________________________________________________________

}//	Class end
