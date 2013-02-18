<?php
final class AddEditGoodsPane extends PAddEditPane{

	public function __construct( $Owner ){
    	$buttons	= array(
    		array (	//	Button to save info
    			'name'		=> 'btn_save',
    			'type'		=> 'submit',
    			'is_dis'	=> TRUE,
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

		'fail: function(e,data){var a = 1;alert(100);},'.

		'done:function(e,data){'.
			'$.each(data.result.files,function(index,file){'.
// 				'$("<p/>").text(file.name).appendTo(document.body);'.
				'$("#main_url").val(file.url);'.
				'$("#thumb_url").val(file.thumbnail_url);'.
				'$("#fname").val(file.name);'.


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
		global $gl_Path;

		$this->delUpload();
		$img_file	= ( $old_info['id'] == NULL ) ? _PPSK_DUMMY_IMG : $old_info['id'].'.jpg';

		copy( $gl_Path.'img/assortment/'.$img_file, $gl_Path.'upload/files/'.$img_file );
		copy( $gl_Path.'img/assortment/thumbnail/'.$img_file, $gl_Path.'upload/files/thumbnail/'.$img_file );


		$db_obj	= new PDbl( $this );
		$old_info	= $db_obj->getRow( $this->mRecId, TRUE );

		$tanindex	= 1;
		$lines	= &$this->mLines;
		$lines	.= $this->getInputLineContent( 'name', 'text', _PNAME1._PPSK_ASTERISK, $old_info['name'], $tanindex++, self::_onchange );
		$lines	.= $this->getInputLineContent( 'cku', 'text', _GOOD_ARTICLE._PPSK_ASTERISK, $old_info['cku'], $tanindex++, self::_onchange );

		$lines	.=

'<tr>'.
	'<td colspan="2" id="fileupload_cnt_td" class="edit_pane_content_td">'.

'<input type="hidden" id="main_url" name="main_url" value="">'.
'<input type="hidden" id="thumb_url" name="thumb_url" value="">'.
'<input type="hidden" id="fname" name="fname" value="">'.

'<table cellpadding="0" cellspacing="0" class="AddEditGoodsPaneN_uploadTable">'.
	'<tr>'.
		'<td class="AddEditGoodsPaneN_uploadTd">'.
			'<input type="file" id="fileupload" name="files[]" data-url="upload/loader.php" multiple class="AddEditGoodsPaneN_uploadInput"/>'.
			'<button class="AddEditGoodsPaneN_uploadBtn">'._GOOD_IMAGE.'</button>'.
		'</td>'.

		'<td>'.
			'<div class="AddEditGoodsPaneN_imgExternDiv">'.
				'<div id="img_preview" class="AddEditGoodsPaneN_imgInnerDiv" '.
					'style="background-image:url(\'upload/files/thumbnail/dummy.jpg\');"></div>'.
			'</div>'.
		'</td>'.
	'</tr>'.
'</table>'.

	'</td>'.
'</tr>'.
'';

		parent::initHtmlView();
	}
//______________________________________________________________________________

	protected function isValidData( &$formValues ){


Log::_log(print_r( $formValues, TRUE));

		$formValues['is_valid']	= TRUE;
	}
//______________________________________________________________________________

/**
 * deletes all files from upload folders. Don't do in static. It used in xajax actions.
 */
	public function delUpload(){
		array_map( 'unlink', glob( 'upload/files/*.*' ));
		array_map( 'unlink', glob( 'upload/files/thumbnail/*.*'));
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
