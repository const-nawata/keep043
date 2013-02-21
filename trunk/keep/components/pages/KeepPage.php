<?php
class KeepPage extends Core{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	public function __get( $property ){
		if( property_exists( 'KeepPage', $property )){
			return $this->$property;
		}else{
			return parent::__get( $property )  ;
		}
	}
//______________________________________________________________________________

	public function __set( $property, $value=NULL ){
		if( property_exists( 'KeepPage', $property )){
			$this->$property = $value;
		}else{
			parent::__set( $property, $value );
		}
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	public function getJsCode(){
		$string	= _SET_JS_NULL;
		return $string;
	}
//______________________________________________________________________________

	public function initHtmlView( $view = '' ){
		if( $view == NULL ){
			$const_name	= _TAB_PRMPT.$_SESSION[ 'tab_code' ];
			$view		=_SORRY." Страничка: ".constant( $const_name );
		}
		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

}//	Class end
