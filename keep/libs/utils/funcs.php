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
	$content	= _WELCOME;
	$level		= $_SESSION['level'];

	$db_obj	= new KeepDbl();
	switch( $level ){
		case _PPSK_LEVEL_GUEST: $user_name	= _GUEST; break;

		case 'admin':
		case 'manager':
			$user_name	= $db_obj->getUserName( $_SESSION['user_id'], $level );
			break;

		default:
			Log::_log( _EX.'Undefind user level. On line: '.__LINE__.' in '.addslashes( __FILE__ ).'.' );
	}

	return $content.' '.$user_name;
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
