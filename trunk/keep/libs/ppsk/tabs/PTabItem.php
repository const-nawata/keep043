<?php
/**
 *
 */
class PTabItem extends Core{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
	//-------------------------------------------------------------------------------------------------

	/**
	 * gets css class prefix for tab tags
	 * @return  string which contain css class prefix
	 * */
	protected function getCssTabSelectionPrefix(){
		return ( $this->mName == $_SESSION[ 'tab_code' ] ) ? 'selected' : 'notSelected';
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * gets tab image
	 * @access public
	 * @return string HTML content
	 * */
	//TODO: create default tab image.
	public function getTabImage(){
		return '';
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//-------------------------------------------------------------------------------------------------

}//		Class end
?>