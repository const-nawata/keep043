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
//--------------------------------------------------------------------------------------------------

	public function initHtmlView(){
		$db_obj	= new Dbl( $this );
		$user_info	= $db_obj->getUserInfoById( $this->mRecId, $this->mOwner->getTargetDbTable() );

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	.= $this->getInputLineContent( 'login', 'text', _LOGIN, $user_info[ 'login' ], $tanindex, self::_onchange ); $tanindex++;
		$lines	.= $this->getInputLineContent( 'email', 'text', _EMAIL_ADDR, $user_info[ 'email' ], $tanindex, self::_onchange ); $tanindex++;
		$lines	.= $this->getInputLineContent( 'firstname', 'text', _USER_NAME, $user_info[ 'firstname' ], $tanindex, self::_onchange ); $tanindex++;
		$lines	.= $this->getInputLineContent( 'surname', 'text', _USER_SURNAME, $user_info[ 'surname' ], $tanindex, self::_onchange ); $tanindex++;

		$onchange	=
		self::_onchange.
    		"xajax_onHandler( \"".$this->getHandleResourceString( 'onChangeCountry', get_class( $this ) )."\", {\"country_id\":this.value,\"inst\":document.getElementById( \"inst\" ).value } );";

		$this->mOptions	= $db_obj->getCountriesList();
		$lines	.= $this->getSelBoxLineContent( 'country_id', _COUNTRY, $user_info[ 'country_id' ], $tanindex, $onchange ); $tanindex++;

		$country_id = ( !$this->mRecId ) ? $this->mOptions[ 0 ][ 'id' ] : $user_info[ 'country_id' ];
		$this->mOptions	= $db_obj->getCitiesList( $country_id );
		$lines	.= $this->getSelBoxLineContent( 'city_id', _CITY, $user_info[ 'city_id' ], $tanindex, self::_onchange ); $tanindex++;

		$lines	.= $this->getTextareaLineContent( 'info', _INFO, $user_info[ 'info' ], $tanindex, self::_onchange ); $tanindex++;

		$lines	.= $this->getInputLineContent( 'pass1', 'password', _NEW_PASSWORD, $user_info[ 'password' ], $tanindex, self::_onchange ); $tanindex++;
		$lines	.= $this->getInputLineContent( 'pass2', 'password', _CONF_PASSWORD, $user_info[ 'password' ], $tanindex, self::_onchange ); $tanindex++;
		parent::initHtmlView();
	}
//--------------------------------------------------------------------------------------------------

	protected function isValidData( &$formValues ){
		if( _EMPTY == $formValues[ 'pass1' ] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, "`"._NEW_PASSWORD."`" );
			$formValues	= array( 'focus_id' => 'pass1', 'description' => $message,  'is_valid' => false );
			return;
		}
		if( _EMPTY == $formValues[ 'pass2' ] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, "`"._CONF_PASSWORD."`" );
			$formValues	= array( 'focus_id' => 'pass2', 'description' => $message,  'is_valid' => false );
			return;
		}

		if( $formValues[ 'pass1' ] != $formValues[ 'pass2' ] ){
			$formValues	= array( 'focus_id' => 'pass1', 'description' => _MESSAGE_PASSWORDS_MISMATCH, 'is_valid' => false );
			return;
		}

		$formValues[ 'is_valid' ]	= true;

	}
//--------------------------------------------------------------------------------------------------

	protected function prepareData( &$formValues ){
		$this->mSaveData	= array(
		array( 'id',		$formValues[ 'id' ],		NULL ),
		array( 'firstname',	$formValues[ 'firstname' ],	'firstname' ),
		array( 'surname',	$formValues[ 'surname' ],	'surname' ),
		array( 'city_id',	$formValues[ 'city_id' ],	'city_id' ),
		array( 'level',		_LEVEL_CLIENT,				NULL ),
		array( 'info',		$formValues[ 'info' ],		'info' ),
		array( 'login',		$formValues[ 'login' ],		'login', _LOGIN ),
		array( 'email',		$formValues[ 'email' ],		'email' ),
		array( 'owner_id',	$_SESSION[ 'user_id' ],		NULL ),
		array( 'password',	$formValues[ 'pass1' ],		'pass1' )
		);
		parent::prepareData( $formValues );
	}
//--------------------------------------------------------------------------------------------------

	public function onChangeCountry( &$objResponse, $ownerValues ){
		$this->getSessionParams( $ownerValues );
		$class		= $ownerValues[ 'class' ];
		$tabl_obj	= new $class( NULL, true, false );

		$auth_obj = new Authentication();
		if( $auth_obj->isGrantAccess( $tabl_obj->getAccess() ) ){
			$db_obj	= new Dbl( $this );
			$this->mOptions	= $db_obj->getCitiesList( $ownerValues[ 'country_id' ] );
			$objResponse->assign( "city_id_cnt_td", 'innerHTML', $this->getSelBoxContent( 'city_id', 0, 4, self::_onchange ) );
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
//--------------------------------------------------------------------------------------------------

}//	Class end
?>