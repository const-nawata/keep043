<?php
/**
 * Class to create pane to add or edit table record
 * @author Constantine Nawata (nawata@mail.ru)
 *
 */
abstract class PAddEditPane extends PRnd1Pane{
/**
 * record id
 * @access	protected
 * @property integer $mRecId
 */
	protected $mRecId;
	protected $mOptions		= array();

/**
 * HTML content of table lines.
 * @access	protected
 * @property	string  $mLines
 */
	protected $mLines		= _EMPTY;
	protected $mInpCss		= _EMPTY;
	protected $mSelCss		= _EMPTY;
	protected $mPrmCss		= _EMPTY;
	protected $mTarCss		= _EMPTY;
	public $mInitFocus		= _EMPTY;	//	Must be accessed from PTable object

/**
 * contains data to save; this property is used for PDbl insert / update methods.
 *
 * @property array $mSaveData = array(
 * 		array( <db_field_name>, <value>, <html_id>, <prompt> ),
 * 		array( <db_field_name>, <value>, <html_id>, <prompt> ),
 * 					...
 * 		array( <db_field_name>, <value>, <html_id>, <prompt> )
 * )
 * <db_field_name>	value is mandatory.
 * <value> value is mandatory.
 * <html_id> is optional value and is made sense if duplicate values DB errors may accur. In this case it is MANDATORY!
 * IMPORTANT!!! One of $mSaveData items must have <db_field_name> = 'id'
 * <prompt> value is optional. This value is used in validation messages.
 */
	public $mSaveData		= array();	//	Must be accessed from PDbl object

/**
 * Constructor
 * @access	public
 * @param	object $Owner - object instanse to which object of this class belongs.
 * @return	void
 */
    public function __construct( $Owner ){

    	$this->mForm	= array(
    		'event'		=> 'onsubmit',
    		'handler'	=> "xajax_onHandler( \"".$this->getHandleResourceString( 'saveInfo', get_class( $this ) )."\", xajax.getFormValues( this ) ); return false;"
    	);

    	$this->mButtons	= array(
    		array (	//	Button to save info
    			'name'		=> 'btn_save',
    			'type'		=> 'submit',
    			'is_dis'	=> true,
    			'prompt'	=> _PPSK_SAVE,
    			'hint'		=> _PPSK_SAVE,
    			'css_dis'	=>'btn_disabled',
    			'css_ovr'	=>'btn_over'
    		),
    		array(	//	Button to cancel info
    			'name'		=> 'btn_cancel',
    			'prompt'	=> _PPSK_CANCEL,
    			'hint'		=> _PPSK_CANCEL,
    			'css_ovr'	=> 'btn_over',
    			'handlers'	=> array(
    				'onclick'	=> array(
    					'handler'	=> "removeElement( \"pane_container\" );removeElement( \"veil\" );"
    				)
    			)
    		)
    	);
		parent::__construct( $Owner );
    }
//--------------------------------------------------------------------------------------------------

/**
 * creates HTML content
 * @access	public
 * @return	void
 */
    public function initHtmlView(){
    	$owner_class	= get_class( $this->mOwner );

    	$this->setHiddenInput( 'inst', $this->encipherFilledValue( $owner_class.( ( $this->mRecId ) ? '.'.self::_edit : '.'.self::_add ) ) );

    	$this->mLines	.= "<tr><td colspan='2' style='height: 10px; font-size: 1px;'>&nbsp;</td></tr>";
    	$this->mContent	= "<table class='editPaneTbl' cellpadding='0' cellspacing='0'>".$this->mLines."</table>";
    	parent::initHtmlView();
    }
//--------------------------------------------------------------------------------------------------

    public function getTargetDbTable(){
		return $this->mOwner->getTargetDbTable();
    }
//--------------------------------------------------------------------------------------------------

    const _onchange = "setElementEnabled( \"btn_save\", \"\" );";
    const _edit	= 1;
    const _add	= 2;

    protected function getSelBoxContent( $htmlId, $selOpt, $tabindex, $onchange = '' ){
    	$options	= &$this->mOptions;
    	$content	= "<select name='".$htmlId."' id='".$htmlId."' class='".$this->mSelCss."' tabindex='".$tabindex."' onchange='".$onchange."'>";
	    foreach( $options as $option ){
			$is_sel = ( $option[ 'id' ] == $selOpt ) ? " selected='selected' " : '';
	    	$content	.= "<option value='".$option[ 'id' ]."'$is_sel>".$option[ 'name' ]."</option>";
	    }
	    $content	.= "</select>";

	    return $content;
    }
//--------------------------------------------------------------------------------------------------

    protected function getSelBoxLineContent( $htmlId, $prompt, $selOpt, $tabindex, $onchange = '' ){
    	return
"<tr>
	<td id='".$htmlId."_prmpt_td' class='edit_pane_prmpt ".$this->mPrmCss."'>".$prompt."</td>
	<td id='".$htmlId."_cnt_td' class='edit_pane_content_td'>".$this->getSelBoxContent( $htmlId, $selOpt, $tabindex, $onchange )."</td>
</tr>";
    }
//--------------------------------------------------------------------------------------------------

    protected function getInputLineContent( $htmlId, $type, $prompt = '', $value = '', $tabindex = '', $onchange = '' ){
    	return
"<tr>
	<td id='".$htmlId."_prmpt_td' class='edit_pane_prmpt ".$this->mPrmCss."'>".$prompt."</td>
	<td id='".$htmlId."_cnt_td' class='edit_pane_content_td'><input type='".$type."' name='".$htmlId."' id='".$htmlId."' value='".$value."' class='".$this->mInpCss."' tabindex='".$tabindex."' onkeyup='".$onchange."' /></td>
</tr>";
    }
//--------------------------------------------------------------------------------------------------

    protected function getTextareaLineContent( $htmlId, $prompt = '', $value = '', $tabindex = '', $onchange = '' ){
    	return
"<tr>
	<td id='".$htmlId."_prmpt_td' class='edit_pane_prmpt ".$this->mPrmCss."'>".$prompt."</td>
	<td id='".$htmlId."_cnt_td' class='edit_pane_content_td'><textarea name='".$htmlId."' id='".$htmlId."' class='".$this->mTarCss."' tabindex='".$tabindex."' onkeyup='".$onchange."'>".$value."</textarea></td>
</tr>";
    }
//--------------------------------------------------------------------------------------------------

    public function setRecId( $recId ){
		$this->mRecId	= $recId;
    }
//--------------------------------------------------------------------------------------------------

    protected function getSessionParams( &$formValues ){
		$formValues[ 'inst' ]	= $this->decipherFilledValue( $formValues[ 'inst' ] );
//		list( $formValues[ 'class' ], $action_type )	= explode( '.', $formValues[ 'inst' ] );
		list( $formValues[ 'class' ], $formValues[ 'action_type' ] )	= explode( '.', $formValues[ 'inst' ] );

//		switch( $action_type )
		switch( $formValues[ 'action_type' ] ){
			case self::_edit:	$formValues[ 'id' ]	= $_SESSION[ 'tables' ][  $formValues[ 'class' ] ][ 'line_id' ]; break;
			case self::_add:	$formValues[ 'id' ]	= NULL; break;
		}
    }
//--------------------------------------------------------------------------------------------------

    private function saveData(){
    	if( count( $this->mSaveData ) ){
	    	$db_obj	= new PDbl( $this );
	    	foreach( $this->mSaveData as $items ){
	    		if( $items[ 0 ] == 'id' ){ break; }
	    	}

	        if( $items[ 1 ] ){
	        	$result	= $db_obj->updateRow();
	        	$result[ 'id' ]	= $items[ 1 ];
				return $result;
	    	}else{
	    		return $db_obj->addRow();
	    	}
    	}
    }
//--------------------------------------------------------------------------------------------------

    protected function isValidData( &$formValues ){
		$formValues[ 'is_valid' ]	= true;
    }
//--------------------------------------------------------------------------------------------------

    protected function prepareData( &$formValues ){
    	foreach( $this->mSaveData as &$items ){
    		if( !isset( $items[ 2 ] ) ){ $items[ 2 ] = NULL; }
    		if( !isset( $items[ 3 ] ) ){ $items[ 3 ] = _EMPTY; }
    	}
    }
//--------------------------------------------------------------------------------------------------

//	Handlers	<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>

    public function saveInfo( &$objResponse, &$formValues ){
		$this->getSessionParams( $formValues );
		$class			= $formValues[ 'class' ];
		$tabl_obj		= new $class( NULL, true, false );
		$this->mOwner	= $tabl_obj;
		$auth_obj		= new Authentication();

		if( $auth_obj->isGrantAccess( $tabl_obj->getAccess() ) ){
	    	$this->isValidData( $formValues );
			if( !$formValues[ 'is_valid' ] ){
				$this->showAlertHandler( $objResponse, array( 'message' => $formValues[ 'description' ], 'focus' => $formValues[ 'focus_id' ] ) );
				return array( 'is_error' => true );
			}else{
				$this->prepareData( $formValues );

				$result	= $this->saveData();

				if( $result[ 'is_error' ] ){
					$this->showAlertHandler( $objResponse, array( 'message' => $result[ 'description' ], 'focus' => $result[ 'focus_id' ] ) );
					return $result;
				}else{
					$_SESSION[ 'tables' ][  $class ][ 'line_id' ] = $formValues[ 'id' ] = $result[ 'id' ];
					$tabl_obj->initHtmlView( true );
					$objResponse->assign( $class."_container", 'innerHTML', $tabl_obj->getHtmlView() );
				 	$objResponse->addRemove( 'pane_container' );
				 	$objResponse->addRemove( 'veil' );
				}
			}
		}else{
			$objResponse = $this->doAccessDenied();
		}
		return $result;
    }
//--------------------------------------------------------------------------------------------------

    public function __destruct(){
    	parent::__destruct();
    }
//--------------------------------------------------------------------------------------------------

}//	Class end
?>