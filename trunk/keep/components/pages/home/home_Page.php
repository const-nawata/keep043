<?php
require_once( $gl_pagesPath.'home/NewsPane1.php' );
require_once( $gl_pagesPath.'home/SlidesPane1.php' );

class home_Page extends PPage{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		$this->initHtmlView();
	}
//______________________________________________________________________________

	private function getRunningMessage(){
		if( 0 ){
			$sql_string = "SELECT welcome FROM settings";
			$result = mysql_query($sql_string);
			$row = mysql_fetch_assoc($result);
			$content = $row['welcome'];
		}else{
			$content = "Welcome test message which was created by program.";
		}

		$content	= trim($content);
		return $content;
	}
//______________________________________________________________________________

	private function getDefaultSlides(){
		$width	= 200;
		$height	= 110;

		return array(
			'path'	=> './img/assortment/default/',
			'files' => array(
				0=>		array( 'name' => 'as0000000001.png',	'width' => $width, 'height' => $height ),
				1=>		array( 'name' => 'as0000000001_1.png',	'width' => $width, 'height' => $height ),
				2=>		array( 'name' => 'as0000000001_2.png',	'width' => $width, 'height' => $height ),
				3=>		array( 'name' => 'as0000000002.png',	'width' => $width, 'height' => $height ),
				4=>		array( 'name' => 'as0000000002_1.png',	'width' => $width, 'height' => $height ),
				5=>		array( 'name' => 'as0000000002_2.png',	'width' => $width, 'height' => $height ),
				6=>		array( 'name' => 'as0000000003.png',	'width' => $width, 'height' => $height ),
				7=>		array( 'name' => 'as0000000003_1.png',	'width' => $width, 'height' => $height ),
				8=>		array( 'name' => 'as0000000003_2.png',	'width' => $width, 'height' => $height ),
				9=>		array( 'name' => 'as0000000004.png',	'width' => $width, 'height' => $height ),
				10=>	array( 'name' => 'as0000000004_1.png',	'width' => $width, 'height' => $height ),
				11=>	array( 'name' => 'as0000000004_2.png',	'width' => $width, 'height' => $height )
			)
		);
	}
//______________________________________________________________________________

	private function getSlides(){
		$slides	= $this->getDefaultSlides();
		return $slides;

	}
//______________________________________________________________________________

	public function getJsCode(){
		$slides = $this->getSlides();

		$files_js	= json_encode( $slides['files'] );

		return
"scroller  = new jsScroller(document.getElementById('News'), "._NEWS_SCRL_WIDTH.", "._NEWS_SCRL_HEIGHT.");".
"scrollbar = new jsScrollbar (document.getElementById('Scrollbar-Container'), scroller, false);".
"sl_set = {'id':'slides','width':200,'height':500,'layout':'v','dir':1,'speed':5,'space':10,'path':'".$slides['path']."'};".
"r_txt_obj = new runningTextPx('r_txt_obj', 'scrlTxt', 1117, '".$this->getRunningMessage()."', 20);".
"vslides = new createSlideShow('vslides', sl_set, ".$files_js.");";
	}
//______________________________________________________________________________

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
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

}//	Class end
