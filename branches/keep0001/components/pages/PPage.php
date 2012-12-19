<?php
abstract class PPage extends Core{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

	public function getJsCode(){
		$string	= _SET_JS_NULL;
		return $string;
	}
	//--------------------------------------------------------------------------------------------------

	public function initHtmlView( $view = NULL ){
		if( $view == NULL ){
			$const_name	= _TAB_PRMPT.$_SESSION[ 'tab_code' ];
			$view		=_SORRY." Страничка: ".constant( $const_name );
		}
		parent::initHtmlView( $view );
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>