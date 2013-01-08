<?php
/**
 * build HTML string to show array or object content.
 * @param array $array to convert to string
 * @param strint $arrName name of array
 * @return string HTML content
 */

/**
 * gets welcome text due login info
 * @return string
 * */
function getWelcomeText(){
	$content = _WELCOME;

	$db_obj	= new KeepDbl();
	switch( $_SESSION[ 'level' ] ){
		case _PPSK_LEVEL_GUEST: $user_name	= _GUEST; break;

		case _LEVEL_ADMIN:
		case _LEVEL_MANAGER:
			$user_info	= $db_obj->getUserInfoById( $_SESSION[ 'user_id' ], 'managers' );
			$user_name	= ( $user_info ) ? $user_info[ 'firstname' ]." ".$user_info[ 'surname' ] : '';
			break;

		default:
			throw new Exception( _EX."Undefind user level. On line: ".__LINE__." in ".addslashes( __FILE__ )."." );
	}

	return $content." ".$user_name;
}
//______________________________________________________________________________

/**
 * gets welcome text due login info
 * @return string
 * */
function iniJsEnvironment(){
	return
'<script type="text/javascript">'.
'var _TAB_LEFT_IMG_SFX="'._TAB_LEFT_IMG_SFX.'";'.
'var _TAB_CENTER_IMG_SFX="'._TAB_CENTER_IMG_SFX.'";'.
'var _TAB_RIGHT_IMG_SFX="'._TAB_RIGHT_IMG_SFX.'";'.

'var scroller=null;'.
'var scrollbar=null;'.
'var vslides=null;'.
'var r_txt_obj=null;'.
'var sl_set=null;'.
'</script>';
}
//______________________________________________________________________________
