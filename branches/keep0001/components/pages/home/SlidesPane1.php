<?php
class SlidesPane1 extends PRnd1Pane {
	public function __construct($Owner){
		$this->mContent		= "<div class='slidesDiv'><div id='slides' class='slides'></div></div>";
		$this->mWidth		= 240;
		$this->mHeigth		= 520;
		$this->mBrdClr	= _PANE_BORDER_COLOR;
		$this->mBkgClr		= _GEN_BKGRND_COLOR;

		parent::__construct($Owner);
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------  mBorderClr

}//	Class end
?>