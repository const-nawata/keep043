<?php
class NewsPane1 extends PRnd1Pane{
	public function __construct($Owner){
		$this->mContent		= $this->getScrollerContainer1(810, 495, $this->getNewsContent ());
		$this->mWidth		= 860;
		$this->mHeigth		= 520;
		$this->mBrdClr		= _PANE_BORDER_COLOR;
		$this->mBkgClr		= _GEN_BKGRND_COLOR;

		parent::__construct($Owner);
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------

	private function getNewsContent(){
		$db_obj	= new Dbl( $this );
		$news = $db_obj->getNews();
		$is_news_exist = false;
		$news_content = "
<table cellpadding='0' cellspacing='0'>";
		foreach ($news as $news_item){
			$is_news_exist = true;
			$news_content .= "
    <tr><td class='newsDateTD'>".$news_item['fdate']."</td><td class='newsContentTD'>".$news_item['content']."</td></tr>";
		}
		(!$is_news_exist) ? $news_content .= "<tr><td>&nbsp;</td></tr>":'';  //  Fictive empty line
		$news_content .= "
</table>";

		return $news_content;
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

	/**
	 * creates view of container with scrolling
	 * */
	private function getScrollerContainer1($width=300, $heigth=300, $content=''){
		$treck_heigth = $heigth - 72;
		$string = "
<table border='0'  cellpadding='0' cellspacing='0' class='scrlCntTbl'>
	<tr>
		<td>
		    <div id='News' class='scrollerOuter' style='width:".$width."px; height:".$heigth."px;'>
		        <div class='Scroller-Container'>".$content."</div>
		    </div>
		</td>

		<td class='scrlTrackTd'>
		    <div id='Scrollbar-Container'>
		      <div class='Scrollbar-Up'></div>
		      <div class='Scrollbar-Track' style='height: ".$treck_heigth."px;'>
		        <div class='Scrollbar-Handle'></div>
		      </div>
		      <div class='Scrollbar-Down'></div>
		      </div>
		</td>
	</tr>
</table>";
		return $string;
	}
	//---------------------------------------------------------------------------------------------------------------------------------------------------

}//	Class end
?>