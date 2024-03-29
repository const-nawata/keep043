<?php
require_once($gl_CompsPath.'/pages/lists/panes/AddDelCatsPane.php');

final class AddEditGoodsPane extends PAddEditPane{

	public function __construct( $Owner ){
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

		'fail: function(e,data){var a=1; alert(100);},'.

		'done:function(e,data){'.
			'$.each(data.result.files,function(index,file){'.
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
				self::_onchange.
			'});'.
		'}'.
	'});'.
'});';

		$this->mInitFocus= 'name';
	}
//______________________________________________________________________________

	public function addDelCatsHandler( &$objResponse, $dummy ){
		$obj_pane	= new AddDelCatsPane( $this );
		$obj_pane->initHtmlView();
		$view	= $obj_pane->getHtmlView();

		$objResponse->script(
			"prependDiv('body_id','cats_veil','PPSK_alert_vail_div');".
			"prependDiv('body_id','cats_container','PPSK_pane_alert_container_div Pane_cats_div');"
		);

		$objResponse->assign( 'cats_container', 'innerHTML', $view );

		return $objResponse;
	}
//______________________________________________________________________________ removeElement("pane_container");removeElement("veil");'

	protected function getEditPaneButtons(){
		$buttons	= parent::getEditPaneButtons();
		$buttons[1]['handlers']['onclick']['handler']	= 'removeElement("pane_container");removeElement("veil");'.
								'xajax_onHandler("'.self::getHandleResourceString( 'delUpload', get_class($this)).'",null);';

		$buttons[2]	= $buttons[1];
		$buttons[1]	= $buttons[0];
		$buttons[0]	= array (	//	Button to show categories pane.
    			'name'		=> 'btn_cats',
    			'prompt'	=> _CATEGORIES,
    			'hint'		=> _CATEGORIES,
    			'css_ovr'	=>'btn_over',
				'handlers'	=> array(
					'onclick'	=> array(
						'handler'	=> 'xajax_onHandler("'.self::getHandleResourceString( 'addDelCatsHandler', get_class($this)).'",null);'
					)
				)
    		);

		return $buttons;
	}
//______________________________________________________________________________

	public function initHtmlView( $view = '' ){
		global $gl_Path;

		$this->delUpload();

		$db_obj	= new PDbl( $this );
		$old_info	= $db_obj->getRow( $this->mRecId, TRUE );
		$img_file	= ( $old_info['id'] == NULL ) ? _PPSK_DUMMY_IMG : $old_info['id'].'.jpg';
		$img_file	= ( !file_exists($gl_Path._ASSORT_IMG_PATH.$img_file ) || !file_exists($gl_Path._ASSORT_IMG_PATH._PPSK_THUMB_FOLD.$img_file ))
			? _PPSK_DUMMY_IMG
			: $img_file;

		$dest_img_file	= ( $img_file != _PPSK_DUMMY_IMG ) ? rand( 1, 10000 ).'_'.$img_file : $img_file;

		copy( $gl_Path._ASSORT_IMG_PATH.$img_file, $gl_Path._PPSK_UPLOAD_FILES.$dest_img_file );
		copy( $gl_Path._ASSORT_IMG_PATH._PPSK_THUMB_FOLD.$img_file, $gl_Path._PPSK_UPLOAD_FILES._PPSK_THUMB_FOLD.$dest_img_file );

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
			'<input type="file" id="fileupload" name="files[]" data-url="'._PPSK_UPLOAD_FOLD.'loader.php" multiple class="AddEditGoodsPaneN_uploadInput"/>'.
			'<button class="AddEditGoodsPaneN_uploadBtn">'._GOOD_IMAGE.'</button>'.
		'</td>'.

		'<td>'.
			'<div class="AddEditGoodsPaneN_imgExternDiv">'.
				'<div id="img_preview" class="AddEditGoodsPaneN_imgInnerDiv" '.
					'style="'.
						'background-image:url(\''._PPSK_UPLOAD_FILES._PPSK_THUMB_FOLD.$dest_img_file.'\');'.
						'background-repeat:no-repeat;'.
						'background-position:center;'.
				'"></div>'.
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
		$this->__set( 'mForm', $formValues );
		$formValues['is_valid']	= TRUE;
	}
//______________________________________________________________________________

	protected function saveData(){
		global $gl_Path;

		$result	= parent::saveData();
		if( !$result['is_error'] ){
			$form_vals	= $this->__get( 'mForm' );
			$img_file	= $form_vals['fname'];

			if( $img_file != '' ){
				$id	= $result['id'];
				copy( $gl_Path._PPSK_UPLOAD_FILES.$img_file, $gl_Path._ASSORT_IMG_PATH.$id.'.jpg' );
				copy( $gl_Path._PPSK_UPLOAD_FILES._PPSK_THUMB_FOLD.$img_file, $gl_Path._ASSORT_IMG_PATH._PPSK_THUMB_FOLD.$id.'.jpg' );
			}
		}

		return $result;
	}
//______________________________________________________________________________

/**
 * deletes all files from upload folders. Don't do in static. It used in xajax actions.
 */
	public function delUpload(){
		array_map( 'unlink', glob( _PPSK_UPLOAD_FILES.'*.*' ));
		array_map( 'unlink', glob( _PPSK_UPLOAD_FILES._PPSK_THUMB_FOLD.'*.*'));
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
