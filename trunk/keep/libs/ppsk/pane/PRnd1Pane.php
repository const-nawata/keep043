<?php
require_once($gl_PpskPath.'pane/PPane.php');

/**
 * See doc.txt before use this class.
 --------------------
 General Remarks
 --------------------
 This component was created and tested for  PHP version 5.2.11; Apache version 2.2.4.
 @author C.Nawata (nawataster@gmail.com)
 */
class PRnd1Pane extends PPane{

	public function __get( $property ){
		return ( property_exists( 'PRnd1Pane', $property ))
			? $this->$property
			: parent::__get( $property )  ;
	}
//______________________________________________________________________________

	public function __set( $property, $value=NULL ){
		if( property_exists( 'PRnd1Pane', $property )){
			$this->$property = $value;
		}else{
			parent::__set( $property, $value );
		}
	}
//______________________________________________________________________________

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

	/**
	 * sets HTML view for rounded corners pane.
	 * @param	string $view - fictive parameter. This parameter is set for PHP strict compatibility
	 * @return	void
	 */
	public function initHtmlView( $view = '' ){
			$height_ins	= $this->mHeigth - 26;

			$pane_view =
"<div style='width:".$this->mWidth."px;'>
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

	<div class='PPSK_ugCnt1_fg' style='background-color:".$this->mBkgClr."; height: ".$height_ins."px; border-color:".$this->mBrdClr.";'>".
    $this->getInnerHtmlContent().
	"</div>

	<b class='PPSK_ugCnt1_' style='width:".$this->mWidth."px;'>
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

		parent:: initHtmlView( $pane_view );
	}
//______________________________________________________________________________

}// End of class
