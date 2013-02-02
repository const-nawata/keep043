<?php
class AddEditCountryPane extends PAddEditPane{

	public function __construct( $Owner ){
		parent::__construct( $Owner );

		$this->mTitle		= _COUNTRY_NAME;

		$this->mName	= 'AddEditCountryPaneN';

		$this->mTarCss		= 'edit_pane_txt_ar';
		$this->mInpCss		= 'edit_pane_input';
		$this->mSelCss		= 'edit_pane_sel';

		$this->mWidth		= 455;
		$this->mHeigth		= 130;

		$this->mBrdClr		= _PANE_BORDER_COLOR;
		$this->mBkgClr		= _GEN_BKGRND_COLOR;
		$this->mInitFocus	= 'country';
	}
//______________________________________________________________________________

	public function initHtmlView(){
		$db_obj			= new PDbl( $this );
		$old_info	= $db_obj->getRow( $this->mRecId, TRUE );

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	= '';

		$lines	.= $this->getInputLineContent( 'country', 'text', _PNAME1._PPSK_ASTERISK, $old_info['name'], $tanindex, self::_onchange ); $tanindex++;
		parent::initHtmlView();
	}
//______________________________________________________________________________

	protected function isValidData( &$formValues ){
		$formValues['country']	= trim( $formValues['country'] );
		if( '' == $formValues['country'] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, '`'._PNAME1.'`' );
			$formValues	= array( 'focus_id' => 'country', 'description' => $message,  'is_valid' => FALSE );
			return;
		}
		$formValues['is_valid']	= TRUE;
	}
//______________________________________________________________________________

	protected function prepareData( &$formValues ){
		$this->mSaveData	= array(
			array( 'id',	$formValues['id'],		NULL ),
			array( 'name',	$formValues['country'],	'country', _PNAME1 )
		);
		parent::prepareData( $formValues );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
