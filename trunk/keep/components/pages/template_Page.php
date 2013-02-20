<?php
class template_Page extends KeepPage {

	public function __construct() {
		parent::__construct($this);
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------

}
?>