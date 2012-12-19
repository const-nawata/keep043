<?php
require_once($gl_PpskPath."pane/PPane.php");

/**
 * See doc.txt before use this class.
 --------------------
 General Remarks
 --------------------
 This component was created and tested for  PHP version 5.2.11; Apache version 2.2.4.
 @author C.Nawata (nawataster@gmail.com)
 */
abstract class PRnd1Pane extends PPane{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * sets HTML view for rounded corners pane.
	 * @access	public
	 * @param	string $view
	 * @return	void
	 */
	public function initHtmlView( $view = NULL ){
		if( $view == NULL ){
			$height_ins	= $this->mHeigth - 26;

			$view = "
<div style='width: ".$this->mWidth."px;'>
	<b class='PPSK_ugCnt1_'>
      <b class='PPSK_ugCnt1_01' style='background-color:".$this->mBrdClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_02' style='background-color:".$this->mBrdClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_03' style='background-color:".$this->mBrdClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_04' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_05' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_06' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_07' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_08' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_09' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_10' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_11' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_12' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_13' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
	</b>

	<div class='PPSK_ugCnt1_fg' style='background-color:".$this->mBkgClr."; height: ".$height_ins."px; border-color:".$this->mBrdClr.";'>
    ".$this->getInnerHtmlContent()."
	</div>

	<b class='PPSK_ugCnt1_' style='width: ".$this->mWidth."px;'>
      <b class='PPSK_ugCnt1_13' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_12' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_11' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_10' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_09' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_08' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_07' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_06' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_05' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_04' style='background-color:".$this->mBkgClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_03' style='background-color:".$this->mBrdClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_02' style='background-color:".$this->mBrdClr."; border-color:".$this->mBrdClr.";'></b>
      <b class='PPSK_ugCnt1_01' style='background-color:".$this->mBrdClr."; border-color:".$this->mBrdClr.";'></b>
	</b>
</div>";
		}

		parent:: initHtmlView( $view );
	}
	//--------------------------------------------------------------------------------------------------

}// End of class
?>