<?php
class AddEditDepartmentPane extends PAddEditPane{

	public function __construct( $Owner ){
		parent::__construct( $Owner );



		$this->mTitle		= _EDITING." "._DEPARTMENT_ROD;

		$this->mName	= 'AddEditCountryPaneN';

		$this->mTarCss		= 'edit_pane_txt_ar';
		$this->mInpCss		= 'edit_pane_input';
		$this->mSelCss		= 'edit_pane_sel';

		$this->mWidth		= 455;
		$this->mHeigth		= 185;

		$this->mBrdClr		= _PANE_BORDER_COLOR;
		$this->mBkgClr		= _GEN_BKGRND_COLOR;
		$this->mInitFocus	= 'name';
	}
//______________________________________________________________________________

	public function initHtmlView(){
		$db_obj	= new KeepDbl( $this );
		$info	= $db_obj->getDepartmentInfo( $this->mRecId );

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	.= $this->getInputLineContent( 'name', 'text', _PNAME1._PPSK_ASTERISK, $info[ 'name' ], $tanindex, self::_onchange ); $tanindex++;
		$lines	.= $this->getTextareaLineContent( 'info', _INFO, $info[ 'info' ], $tanindex, self::_onchange ); $tanindex++;
		parent::initHtmlView();
	}
//______________________________________________________________________________

	protected function isValidData( &$formValues ){
		$formValues[ 'name' ]	= trim( $formValues[ 'name' ] );

		if( _EMPTY == $formValues[ 'name' ] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, "`"._PNAME1."`" );
			$formValues	= array( 'focus_id' => 'name', 'description' => $message,  'is_valid' => false );
			return;
		}
		$formValues[ 'is_valid' ]	= true;
	}
//______________________________________________________________________________

	protected function prepareData( &$formValues ){
		$this->mSaveData	= array(
			array( 'id',	$formValues['id'],	NULL ),
			array( 'name',	$formValues['name'],'name', _PNAME1 ),
			array( 'info',	$formValues['info'],'info', _INFO )
		);
		parent::prepareData( $formValues );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
