<?php
require_once( $gl_pagesPath.'tableListController.php' );

abstract class multiTablesPage extends PPage{

	/**
	 * @property array $mTablesList => array(
	 * 	[] => array(
	 * 		string [table_code]
	 * 		string [menu_prompt]
	 * 	)
	 * )
	 */
	protected $mTablesList	= array();

	private $mRowSpan	= 0;

//----------------------//-----------------------//-----------------------//----------------------//

	public function __construct( $Owner ){
		parent::__construct( $Owner );
		//    	$this->initHtmlView();
	}
//______________________________________________________________________________

	public function getNotSelectedSellHanlders( $sellCode, $prompt ){
		return array(
			'onmouseover'	=> "setMouseOverCss( this, \"multiTablesPageMenuOverSellTd\" );",
			'onmouseout'	=> "setMouseOutCss( this );",
			'onclick'		=>	"var old_code = document.getElementById( \"prev_sell_code\" ).value;".
								"var old_div_id = \"div_sell_\" + old_code;".
								"var info = {\"old_code\":old_code,".
											"\"new_code\":\"".$sellCode."\",".
											"\"old_prompt\":document.getElementById( old_div_id ).innerHTML,".
											"\"new_prompt\":\"".$prompt."\"};".
								"xajax_onHandler( \"".$this->getHandleResourceString( 'showTable', 'tableListController' )."\", info );"
								);
	}
//______________________________________________________________________________

	public function getSellHtmlContent( $sellCode, $prompt, $handlers, $css ){


		//echo "prompt: $prompt<br>";

		$view	= "<div id='div_sell_".$sellCode."' class='".$css."'";

		foreach( $handlers as $event => $handler ){
			$view	.= " ".$event."='".$handler."'";
		}
		$view	.= ">".$prompt."</div>";
		return $view;
	}
//______________________________________________________________________________

	private function getTableNameSellHtmlContent( $prompt, $sellCode ){
		$tbl_list	= &$this->mTablesList;
		$this->mRowSpan++;
		if( $sellCode != $tbl_list[ 0 ][ 'table_code' ] ){
			$css_class		= "mutiTablesPageMenuSellTd";
			$handlers	= self::getNotSelectedSellHanlders( $sellCode, $prompt );
		}else{
			$handlers	= array();
			$css_class	= "mutiTablesPageMenuSelectedSellTd";
		}

		return
"<td id='td_sell_".$sellCode."'>".self::getSellHtmlContent( $sellCode, $prompt, $handlers, $css_class )."</td>";
	}
//______________________________________________________________________________

	private function getEmptySellHeight(){
		switch( $this->mRowSpan ){
			case 1: $height	= 487; break;
			case 2: $height	= 460; break;
			case 3: $height	= 433; break;
		}
		return $height;
	}
//______________________________________________________________________________

	private function getMultiTablesHtmlContent(){

		$tbl_list	= &$this->mTablesList;

		$rest_menu_items = '';
		foreach( $tbl_list as $ind => &$tbl_params ){
			$tbl_sell_name	= $this->getTableNameSellHtmlContent( $tbl_params['menu_prompt'], $tbl_params['table_code'] );


			( $ind == 0 )
				? $first_name_sell	= $tbl_sell_name
				: $rest_menu_items	.= '<tr>'.$tbl_sell_name.'</tr>';
		}

		$tbl_view	= tableListController::buildSettingsTableHtmlContent( $tbl_list[0]['table_code'] );

		return
'<input id="prev_sell_code" name="prev_sell_code" type="hidden" value="'.$tbl_list[0]['table_code'].'" />'.
'<table class="mutiTablesPageTbl" cellpadding="0" cellspacing="0">'.
	'<tr>'.
		$first_name_sell.
		"<td id='multi_tables_page_container' rowspan='".( $this->mRowSpan + 1 )."' class='multiTablesPageContainerSellTd'>".$tbl_view.'</td>'.
	'</tr>'.$rest_menu_items.
	'<tr><td class="multiTablesPageMenuEmptySellTd" style="height:'.$this->getEmptySellHeight().'px;">&nbsp;</td></tr>'.
'</table>';
	}
//______________________________________________________________________________

	public function initHtmlView(){
		$view	= $this->getMultiTablesHtmlContent();
		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
