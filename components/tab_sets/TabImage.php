<?php
class TabImage extends PTabItem{

	public function __construct( $Owner ){
		parent::__construct( $Owner );
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------

	public function getTabImage(){
		$tab_code	= &$this->mName;
		$handlers	= $this->getHandlersHtml();
		$css_prefix	= $this->getCssTabSelectionPrefix();
		$string =
"<table cellspacing='0' cellpadding='0' border='0'>
    <tr>
        <td><div id='".$tab_code._TAB_LEFT_IMG_SFX."TagId' class='".$css_prefix._TAB_LEFT_IMG_SFX."' ".$handlers.">&nbsp;</div></td>
        <td><div id='".$tab_code._TAB_CENTER_IMG_SFX."TagId' class='".$css_prefix._TAB_CENTER_IMG_SFX."' ".$handlers.">".$this->getPrompt()."</div></td>
        <td><div id='".$tab_code._TAB_RIGHT_IMG_SFX."TagId' class='".$css_prefix._TAB_RIGHT_IMG_SFX."' ".$handlers.">&nbsp;</div></td>
    </tr>
</table>";
		return $string;
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>