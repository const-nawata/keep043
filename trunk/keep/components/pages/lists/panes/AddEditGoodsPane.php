<?php
final class AddEditGoodsPane extends PAddEditPane{

	public function __construct( $Owner ){
    	$buttons	= array(
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
    					'handler'	=> 'removeElement("pane_container");removeElement("veil");'.
								'xajax_onHandler("'.self::getHandleResourceString( 'delUpload', get_class($this)).'",null);'

    				)
    			)
    		)
    	);

    	$this->__set( 'mButtons', $buttons );

    	parent::__construct( $Owner );

		$this->mTitle	= _EDITING.' '._GOOD_PARS_ROD;

		$this->mName	= 'AddEditGoodsPaneN';

		$this->mTarCss	= 'edit_pane_txt_ar';
		$this->mInpCss	= 'edit_pane_input';
		$this->mSelCss	= 'edit_pane_sel';

		$this->mWidth	= 455;
		$this->mHeigth	= 400;

		$this->mBrdClr	= _PANE_BORDER_COLOR;
		$this->mBkgClr	= _GEN_BKGRND_COLOR;


		$this->mJsScript	=
'$(function(){'.
	'$("#fileupload").fileupload({'.
		'dataType:"json",'.

		'done:function(e,data){'.
			'$.each(data.result.files,function(index,file){'.
// 				'$("<p/>").text(file.name).appendTo(document.body);'.
				'$("#prev_file").val(file.url);'.

				'var turl="url(\""+file.thumbnail_url+"\")";'.
				'$("#img_preview").css({'.
					'backgroundImage:turl,'.
					'backgroundRepeat:"no-repeat",'.
// 					'backgroundAttachment:"fixed",'.
					'backgroundPosition:"center"'.
				'});'.
			'});'.
		'}'.
	'});'.
'});';

		$this->mInitFocus= 'name';
	}
//______________________________________________________________________________

	public function initHtmlView( $view = '' ){
		$db_obj	= new PDbl( $this );
		$old_info	= $db_obj->getRow( $this->mRecId, TRUE );

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	.= $this->getInputLineContent( 'name', 'text', _PNAME1._PPSK_ASTERISK, $old_info['name'], $tanindex++, self::_onchange );
		$lines	.= $this->getInputLineContent( 'cku', 'text', _GOOD_ARTICLE._PPSK_ASTERISK, $old_info['cku'], $tanindex++, self::_onchange );

		$lines	.=

'<tr>'.
	'<td colspan="2" id="fileupload_cnt_td" class="edit_pane_content_td">'.

	'<input type="hidden" id="prev_file" name="prev_file" value="">'.

'<table cellpadding="0" cellspacing="0" class="AddEditGoodsPaneN_uploadTable">'.
	'<tr>'.
		'<td class="AddEditGoodsPaneN_uploadTd">'.
			'<input type="file" id="fileupload" name="files[]" data-url="upload/loader.php" multiple class="AddEditGoodsPaneN_uploadInput"/>'.
			'<button class="AddEditGoodsPaneN_uploadBtn">'._GOOD_IMAGE.'</button>'.
		'</td>'.

		'<td><div class="AddEditGoodsPaneN_imgExternDiv"><div id="img_preview" class="AddEditGoodsPaneN_imgInnerDiv"></div></div></td>'.
	'</tr>'.
'</table>'.

	'</td>'.
'</tr>'.



'';

		parent::initHtmlView();
	}
//______________________________________________________________________________

	protected function isValidData( &$formValues ){

		$formValues['is_valid']	= TRUE;
	}
//______________________________________________________________________________

	public function delUpload(){

// 		unlink();

		$fls	= glob( "upload/files/*.*" );

Log::_log("fls:\n".print_r( $fls, TRUE));

// Log::_log("Point do delete upload");
	}
//______________________________________________________________________________

	protected function adjustForm( $formValues ){
		$this->mSaveData	= array(
			array( 'id',	$formValues['id'],	NULL ),
			array( 'name',	$formValues['name'],'name', _PNAME1 ),
			array( 'cku',	$formValues['cku'],'cku', _GOOD_ARTICLE )
		);
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end
