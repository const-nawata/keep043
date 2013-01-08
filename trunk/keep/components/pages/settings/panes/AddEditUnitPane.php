<?php
class AddEditUnitPane extends PAddEditPane{

	public function __construct( $Owner ){
		parent::__construct( $Owner );

		$this->mTitle		= _UNIT_NAME;

		$this->mName	= 'AddEditUnitPaneN';

		$this->mTarCss		= 'edit_pane_txt_ar';
		$this->mInpCss		= 'edit_pane_input';
		$this->mSelCss		= 'edit_pane_sel';

		$this->mWidth		= 455;
		$this->mHeigth		= 155;

		$this->mBrdClr		= _PANE_BORDER_COLOR;
		$this->mBkgClr		= _GEN_BKGRND_COLOR;
		$this->mInitFocus	= 'full_name';
	}
	//--------------------------------------------------------------------------------------------------

	public function initHtmlView(){
		$db_obj	= new KeepDbl( $this );
		$unit_info	= $db_obj->getUnitInfoById( $this->mRecId );

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	= "";

		$lines	.= $this->getInputLineContent( 'full_name', 'text', _PFULL1._PPSK_ASTERISK, $unit_info[ 'full_name' ], $tanindex, self::_onchange ); $tanindex++;
		$lines	.= $this->getInputLineContent( 'brief_name', 'text', _PBRIEF1._PPSK_ASTERISK, $unit_info[ 'brief_name' ], $tanindex, self::_onchange ); $tanindex++;
		parent::initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	protected function isValidData( &$formValues ){
		$formValues[ 'full_name' ]	= trim( $formValues[ 'full_name' ] );
		if( _EMPTY == $formValues[ 'full_name' ] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, "`"._PFULL1."`" );
			$formValues	= array( 'focus_id' => 'full_name', 'description' => $message,  'is_valid' => false );
			return;
		}

		if( _EMPTY == $formValues[ 'brief_name' ] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, "`"._PBRIEF1."`" );
			$formValues	= array( 'focus_id' => 'brief_name', 'description' => $message,  'is_valid' => false );
			return;
		}

		$formValues[ 'is_valid' ]	= true;
	}
	//--------------------------------------------------------------------------------------------------

	protected function prepareData( &$formValues ){
		$this->mSaveData	= array(
		array( 'id',	$formValues[ 'id' ],		NULL ),
		array( 'full_name',	$formValues[ 'full_name' ],	'full_name', _PFULL1 ),
		array( 'brief_name',	$formValues[ 'brief_name' ],	'brief_name', _PBRIEF1 )
		);
		parent::prepareData( $formValues );
	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>