<?php
class AddEditCityPane extends PAddEditPane{

	public function __construct( $Owner = NULL ){
		parent::__construct( $Owner );

		$this->mTitle		=  _CITY_NAME;

		$this->mName	= 'AddEditCityPaneN';

		$this->mTarCss		= 'edit_pane_txt_ar';
		$this->mInpCss		= 'edit_pane_input';
		$this->mSelCss		= 'edit_pane_sel';

		$this->mWidth		= 455;
		$this->mHeigth		= 155;

		$this->mBrdClr		= _PANE_BORDER_COLOR;
		$this->mBkgClr		= _GEN_BKGRND_COLOR;
		$this->mInitFocus		= 'city';
	}
	//--------------------------------------------------------------------------------------------------

	public function initHtmlView(){
		$db_obj	= new KeepDbl( $this );
		$city_info	= $db_obj->getCityInfoById( $this->mRecId );

		$tanindex	= 1;
		$lines	= &$this->mLines;

		$this->mOptions	= $db_obj->getCountriesList();
		$lines	.= $this->getSelBoxLineContent( 'country_id', _COUNTRY._PPSK_ASTERISK, $city_info[ 'country_id' ], $tanindex, self::_onchange ); $tanindex++;

		$lines	.= $this->getInputLineContent( 'city', 'text', _CITY._PPSK_ASTERISK, $city_info[ 'name' ], $tanindex, self::_onchange ); $tanindex++;
		parent::initHtmlView();
	}
	//--------------------------------------------------------------------------------------------------

	protected function isValidData( &$formValues ){
		$formValues[ 'city' ]	= trim( $formValues[ 'city' ] );
		if( _EMPTY == $formValues[ 'city' ] ){
			$message	= sprintf( _MESSAGE_EMPTY_NAME, "`"._CITY."`" );
			$formValues	= array( 'focus_id' => 'city', 'description' => $message,  'is_valid' => false );
			return;
		}
		$formValues[ 'is_valid' ]	= true;
	}
	//--------------------------------------------------------------------------------------------------

	protected function prepareData( &$formValues ){
		$this->mSaveData	= array(
			'id'			=> $formValues[ 'id' ],
			'name'			=> $formValues[ 'city' ],
    		'country_id'	=> $formValues[ 'country_id' ]
		);

		$this->mSaveData	= array(
		array( 'id',			$formValues[ 'id' ],			NULL ),
		array( 'name',			$formValues[ 'city' ],			'city' ),
		array( 'country_id',	$formValues[ 'country_id' ],	'country_id' )
		);



	}
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>