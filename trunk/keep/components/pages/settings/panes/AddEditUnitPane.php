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
//______________________________________________________________________________

	public function initHtmlView(){
		$db_obj	= new PDbl( $this );
		$old_info	= $db_obj->getRow( $this->mRecId, TRUE );

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	= '';

		$lines	.= $this->getInputLineContent( 'full_name', 'text', _PFULL1._PPSK_ASTERISK, $old_info['full_name'], $tanindex++, self::_onchange );
		$lines	.= $this->getInputLineContent( 'brief_name', 'text', _PBRIEF1._PPSK_ASTERISK, $old_info['brief_name'], $tanindex++, self::_onchange );
		parent::initHtmlView();
	}
//______________________________________________________________________________

	protected function isValidData( &$formValues ){
		$formValues['full_name']	= trim( $formValues['full_name'] );
		if( '' == $formValues['full_name'] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, '`'._PFULL1.'`' );
			$formValues	= array( 'focus_id' => 'full_name', 'description' => $message,  'is_valid' => FALSE );
			return;
		}

		if( '' == $formValues['brief_name'] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, '`'._PBRIEF1.'`' );
			$formValues	= array( 'focus_id' => 'brief_name', 'description' => $message,  'is_valid' => FALSE );
			return;
		}

		$formValues['is_valid']	= TRUE;
	}
//______________________________________________________________________________

	protected function prepareData( &$formValues ){
		$this->mSaveData	= array(
		array( 'id',	$formValues['id'],		NULL ),
		array( 'full_name',	$formValues['full_name'],	'full_name', _PFULL1 ),
		array( 'brief_name',	$formValues['brief_name'],	'brief_name', _PBRIEF1 )
		);
		parent::prepareData( $formValues );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
