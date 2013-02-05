<?php
class AddEditClientPane extends PAddEditPane{

	public function __construct( $Owner ){
		parent::__construct( $Owner );

		$this->mTitle		= _USER_PROFILE;

		$this->mName	= 'AddEditClientPaneN';

		$this->mTarCss	= 'edit_pane_txt_ar';
		$this->mInpCss	= 'edit_pane_input';
		$this->mSelCss	= 'edit_pane_sel';

		$this->mWidth	= 455;
		$this->mHeigth	= 390;

		$this->mBrdClr	= _PANE_BORDER_COLOR;
		$this->mBkgClr	= _GEN_BKGRND_COLOR;
		$this->mInitFocus	= 'login';

	}
//______________________________________________________________________________

	public function initHtmlView(){
		$rec_id	= $this->mRecId;

		$db_obj	= new PDbl( $this );

		$old_info	= $db_obj->getRow( $rec_id, TRUE );

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	.= $this->getInputLineContent( 'login', 'text', _LOGIN, $old_info['login'], $tanindex++, self::_onchange );
		$lines	.= $this->getInputLineContent( 'email', 'text', _EMAIL_ADDR, $old_info['email'], $tanindex++, self::_onchange );
		$lines	.= $this->getInputLineContent( 'firstname', 'text', _USER_NAME, $old_info['firstname'], $tanindex++, self::_onchange );
		$lines	.= $this->getInputLineContent( 'surname', 'text', _USER_SURNAME, $old_info['surname'], $tanindex++, self::_onchange );

		$onchange	=
		self::_onchange.
		'xajax_onHandler('.
			"\"".self::getHandleResourceString( 'onChangeCountry', get_class($this))."\",".
			"{\"country_id\":this.value,\"inst\":document.getElementById(\"inst\" ).value}".
		");";

		$countries	=
		$this->mOptions	= $db_obj->getSelBoxList( 'countries' );
		$lines	.= $this->getSelBoxLineContent( 'country_id', _COUNTRY, $old_info['country_id'], $tanindex++, $onchange );

		$country_id = ( !$rec_id ) ? $countries[0]['id'] : $old_info['country_id'];
		$this->mOptions	= $db_obj->getSelBoxList( 'cities', '`country_id`='.$country_id );
		$lines	.= $this->getSelBoxLineContent( 'city_id', _CITY, $old_info['city_id'], $tanindex++, self::_onchange );

		$lines	.= $this->getTextareaLineContent( 'info', _INFO, $old_info['info'], $tanindex++, self::_onchange );

		$lines	.= $this->getInputLineContent( 'pass1', 'password', _NEW_PASSWORD, $old_info['password'], $tanindex++, self::_onchange );
		$lines	.= $this->getInputLineContent( 'pass2', 'password', _CONF_PASSWORD, $old_info['password'], $tanindex++, self::_onchange );
		parent::initHtmlView();
	}
//______________________________________________________________________________

	protected function isValidData( &$formValues ){
		if( '' == $formValues['pass1'] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, '`'._NEW_PASSWORD.'`' );
			$formValues	= array( 'focus_id' => 'pass1', 'description' => $message,  'is_valid' => FALSE );
			return;
		}

		if( '' == $formValues['pass2'] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, '`'._CONF_PASSWORD.'`' );
			$formValues	= array( 'focus_id' => 'pass2', 'description' => $message,  'is_valid' => FALSE );
			return;
		}

		if( $formValues['pass1'] != $formValues['pass2'] ){
			$message	= _MESSAGE_PASSWORDS_MISMATCH;
			$formValues	= array( 'focus_id' => 'pass1', 'description' => $message, 'is_valid' => FALSE );
			return;
		}

		$formValues['is_valid']	= TRUE;

	}
//______________________________________________________________________________

	protected function adjustForm( $formValues ){
		$this->mSaveData	= array(
			array( 'id',		$formValues['id'],		NULL ),
			array( 'firstname',	$formValues['firstname'],	'firstname' ),
			array( 'surname',	$formValues['surname'],	'surname' ),
			array( 'city_id',	$formValues['city_id'],	'city_id' ),
			array( 'level',		'client',				NULL ),
			array( 'info',		$formValues['info'],		'info' ),
			array( 'login',		$formValues['login'],		'login', _LOGIN ),
			array( 'email',		$formValues['email'],		'email' ),
			array( 'password',	$formValues['pass1'],		'pass1' )
		);
	}
//______________________________________________________________________________

	public function onChangeCountry( &$objResponse, $ownerValues ){
		$this->getSessionParams( $ownerValues );
		$class		= $ownerValues['class'];
		$tabl_obj	= new $class( NULL, TRUE, FALSE );

		$auth_obj = new Authentication();
		if( $auth_obj->isGrantAccess( $tabl_obj->getAccess())){
			$db_obj	= new KeepDbl( $this );
			$this->mOptions	= $db_obj->getSelBoxList( 'cities', '`country_id`='.$ownerValues['country_id'] );
			$objResponse->assign( 'city_id_cnt_td', 'innerHTML', $this->getSelBoxContent( 'city_id', 0, 4, self::_onchange ) );
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
