<?php
require_once ($gl_pagesPath."catalogue/TestCatalogueIconButton.php");
class CataloguePane extends PRnd1Pane {

	public function __construct(){
		//    	$this->mName		= 'cataloguePane';
		$this->mTitle		= "Catalogue test Pane";

		$this->mContent	= $this->getCatPaneHtmlContent();//	Individual content
		$this->mWidth	= 550;
		$this->mHeigth	= 380;
		$this->mBrdClr	= _PANE_BORDER_COLOR;
		$this->mBkgClr	= _GEN_BKGRND_COLOR;

		parent::__construct($this);
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	/**
	 * gets individual content of catalogue pane. This method is called in constructor.
	 * @access private
	 * @return string HTML content
	 */
	private function getCatPaneHtmlContent(){
		$btn_obj	= new TestCatalogueIconButton($this);
		$btn_view	= $btn_obj->getHtmlView();

		$view	= "
<div style='padding: 10px;'>
	<div id='tst_div' style='width: 500px; height: 250px; background-color: #99FFFF;'></div>
	$btn_view
</div>
    	";


	return $view;
	}
	//--------------------------------------------------------------------------------------------------

	//This block was left as example for buttons (see above)
	//    public function TstCatBtn1( &$objResponse, $Value ){
	//		$objResponse->addAssign( 'errLoginCont', 'innerHTML', $Value );
	//    }
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>