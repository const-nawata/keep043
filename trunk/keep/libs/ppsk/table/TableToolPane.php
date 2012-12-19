<?php
class TableToolPane extends PPane{
	const _toolPaneWidth	= 98;
	const _toolPaneHeigth	= 25;

	public function __construct( $Owner ){
		$this->mName	= $Owner->mName.'_TableToolPane';

		$this->mWidth	= self::_toolPaneWidth;
		$this->mHeigth	= self::_toolPaneHeigth;

		$this->mButtons	= &$Owner->mToolPaneButtons;
		$this->adjustProperties();

		parent::__construct( $this );
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	private function adjustProperties(){
		foreach( $this->mButtons as $type => &$button ){
			$button[ 'name' ]		= 'btn_'.$type.$this->mName;
			$button[ 'css_dis' ]	= ( !isset( $button[ 'css_dis' ] ) ) ? 'PPSK_'.$type.'RowBtnDisabled' : $button[ 'css_dis' ];
			$button[ 'css_act' ]	= ( !isset( $button[ 'css_act' ] ) ) ? 'PPSK_'.$type.'RowBtnEnabled' : $button[ 'css_act' ];
			$button[ 'css_ovr' ]	= ( !isset( $button[ 'css_ovr' ] ) ) ? 'PPSK_'.$type.'RowBtnOver' : $button[ 'css_ovr' ];
			$button[ 'css_dwn' ]	= ( !isset( $button[ 'css_dwn' ] ) ) ? 'PPSK_'.$type.'RowBtnDown' : $button[ 'css_dwn' ];
		}
	}
	//--------------------------------------------------------------------------------------------------

	public function initHtmlView(){
		$view	= "<div class='PPSK_simple_pane table_tool_pane' style='width: ".$this->mWidth."px; height: ".$this->mHeigth."px; background-color:".$this->mBkgClr."; border-color: ".$this->mBrdClr.";'>".
		$this->getInnerHtmlContent().
    			"</div>";
		parent:: initHtmlView( $view );
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>