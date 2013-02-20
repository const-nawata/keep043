<?php
class news_Page extends KeepPage {
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