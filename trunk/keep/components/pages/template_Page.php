<?php
class template_Page extends PPage {

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