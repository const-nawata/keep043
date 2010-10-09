<?php
class PaneButton extends PButton{

	/**
	 * Constructor
	 * @param object $Owner
	 * @param array $params
	 * @return void
	 */
	public function __construct( $Owner, $params ){
		$this->mName	= $params[ 'name' ];
		$this->mType	= $params[ 'type' ];
		$this->mCssAct	= $params[ 'css_act' ];
		$this->mCssDis	= $params[ 'css_dis' ];

		$this->setPrompt( $params[ 'prompt' ] );
		$this->setHint( $params[ 'hint' ] );


		foreach( $params[ 'handlers' ] as $event => $handler ){
			$this->setHandler( $handler, $event );
		}

		( $params[ 'is_dis' ] ) ? $this->setDisabled() : $this->setEnabled();
		parent::__construct( $Owner );
		$this->initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------
}//	Class end
?>