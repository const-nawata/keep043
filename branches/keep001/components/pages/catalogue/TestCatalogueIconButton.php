<?php
class TestCatalogueIconButton extends PButton{
	public function __construct( $Owner ){
		//    	$this->mName	= "CatBtn1";
		$this->setHint( 'Tst Btn' );
		$this->mCssAct	= 'btnIcoTstAct';
		$this->mCssOvr	= 'btnIcoTstOvr';
		$this->mCssDis	= 'btnIcoTstDis';

		$this->setHandler( array( 'handler'=>"this.className=\"btnIcoTstOvr\"" ), 'onmouseover' );
		$this->setHandler( array( 'handler'=>"this.className=\"btnIcoTstAct\"" ), 'onmouseout' );


		$handler	= array(
    		'handler'=>"tstHandler(\"tst_div\");",
    		'ask'=>"Are you sure?"
    		);

    		$this->setHandler( $handler, 'onclick' );

    		parent::__construct($Owner);
    		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>