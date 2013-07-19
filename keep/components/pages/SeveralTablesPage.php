<?php
// require_once( $gl_pagesPath.'tableListController.php' );

class SeveralTablesPage extends KeepPage{

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
	private $mListContr	= NULL;

//------------------//-----------------//-----------------//-------------------/

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	public function __get( $property ){
		return ( property_exists( 'SeveralTablesPage', $property ))
			? $this->$property
			: parent::__get( $property )  ;
	}
//______________________________________________________________________________

	public function __set( $property, $value=NULL ){
		if( property_exists( 'SeveralTablesPage', $property )){
			$this->$property = $value;
		}else{
			parent::__set( $property, $value );
		}
	}
//______________________________________________________________________________

	public function getNotSelectedSellHanlders( $sellCode, $prompt ){


// Log::_log(print_r( $this->__get('mListContr'), TRUE));

		return array(
			'onmouseover'	=> "setMouseOverCss(this, \"SeveralTablesPageMenuOverSellTd\");",
			'onmouseout'	=> "setMouseOutCss(this);",
			'onclick'		=>
				"var old_code = document.getElementById(\"prev_sell_code\").value;".
				"var old_div_id = \"div_sell_\" + old_code;".
				"var info = {\"old_code\":old_code,".
					"\"new_code\":\"".$sellCode."\",".
					"\"old_prompt\":document.getElementById(old_div_id).innerHTML,".
					"\"new_prompt\":\"".$prompt."\",".
					'"class":"'.get_class( $this ).'"'.
				"};".
// 				"xajax_onHandler( \"".self::getHandleResourceString( 'showTable', 'tableListController' )."\", info);"
				"xajax_onHandler( \"".self::getHandleResourceString( 'showTable', get_class( $this->__get('mListContr')))."\", info);"
		);
	}
//______________________________________________________________________________

	public static function getSellHtmlContent( $sellCode, $prompt, $handlers, $css ){
		$view	= '<div id="div_sell_'.$sellCode.'" class="'.$css.'"';

		foreach( $handlers as $event => $handler ){
			$view	.= ' '.$event."='".$handler."'";
		}
		$view	.= '>'.$prompt.'</div>';
		return $view;
	}
//______________________________________________________________________________

	private function getTableNameSellHtmlContent( $prompt, $sellCode ){
		$tbl_list	= &$this->mTablesList;

		$this->mRowSpan++;
		if( $sellCode != $tbl_list[0]['table_code'] ){
			$css_class		= 'SeveralTablesPageMenuSellTd';
			$handlers	= $this->getNotSelectedSellHanlders( $sellCode, $prompt );
		}else{
			$handlers	= array();
			$css_class	= 'SeveralTablesPageMenuSelectedSellTd';
		}

		return
'<td id="td_sell_'.$sellCode.'">'.self::getSellHtmlContent( $sellCode, $prompt, $handlers, $css_class ).'</td>';
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

	public function initHtmlView( $view = '' ){
		$tbl_list	= &$this->mTablesList;

		$rest_menu_items = '';
		foreach( $tbl_list as $ind => &$params ){
			$tbl_sell_name	= $this->getTableNameSellHtmlContent( $params['menu_prompt'], $params['table_code'] );

			( $ind == 0 )
				? $first_name_sell	= $tbl_sell_name
				: $rest_menu_items	.= '<tr>'.$tbl_sell_name.'</tr>';
		}

		$tbl_code	= $tbl_list[0]['table_code'];

		$tview	=
'<input id="prev_sell_code" name="prev_sell_code" type="hidden" value="'.$tbl_code.'" />'.
'<table class="SeveralTablesPageTbl" cellpadding="0" cellspacing="0">'.
	'<tr>'.
		$first_name_sell.
		'<td id="multi_tables_page_container" rowspan="'.($this->mRowSpan + 1).'" class="SeveralTablesPageContainerSellTd">'.
			$this->mListContr->buildSelectedTableHtmlContent( $tbl_code ).
		'</td>'.
	'</tr>'.$rest_menu_items.
	'<tr><td class="SeveralTablesPageMenuEmptySellTd" style="height:'.$this->getEmptySellHeight().'px;">&nbsp;</td></tr>'.
'</table>';

		parent::initHtmlView( $tview );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
