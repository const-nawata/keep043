<?php
class contact_Page extends PPage {

	public function __construct($Owner) {
		parent::__construct($Owner);
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------

}
?>