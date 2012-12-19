<?php
require_once( $gl_pagesPath."home/NewsPane1.php" );
require_once( $gl_pagesPath."home/SlidesPane1.php" );
class home_Page extends PPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->initHtmlView();
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

	public function getJsCode(){
		$db_obj	= new Dbl( $this );
		$slides = $db_obj->getSlides();
		$json = new Services_JSON();
		$files_js = $json->encode( $slides[ 'files' ] );

		return
"scroller  = new jsScroller(document.getElementById('News'), "._NEWS_SCRL_WIDTH.", "._NEWS_SCRL_HEIGHT.");".
"scrollbar = new jsScrollbar (document.getElementById('Scrollbar-Container'), scroller, false);".
"sl_set = {'id':'slides','width':200,'height':500,'layout':'v','dir':1,'speed':5,'space':10, 'path':'".$slides[ 'path' ]."'};".
"r_txt_obj = new runningTextPx('r_txt_obj', 'scrlTxt', 1117, '".$db_obj->getRunningMessage()."', 20);".
"vslides = new createSlideShow('vslides', sl_set, ".$files_js.");";
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

	public function initHtmlView(){
		$news_pane		= new NewsPane1( $this );
		$slides_pane	= new SlidesPane1( $this );
		$view	=
"<table cellpadding='0' cellspacing='0' border='0'>".
    "<tr><td class='outerBlockTitle'>"._TITLE_GENERAL_INFO."</td><td></td></tr>".
    "<tr><td class='newsBlockTD'>".$news_pane->getHtmlView()."</td><td class='slidesBlockTD'>".$slides_pane->getHtmlView()."</td></tr>".
    "<tr><td colspan='2' class='wlcmTD'><div id='scrlTxt' class='wlcmTxt'></div></td></tr>".
"</table>";
		parent::initHtmlView( $view );
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>