<?php
class PagingButton extends PButton{
	private $mPaging;
	private $mPgInfo;
	public $mNum	= NULL;
	public $mBntName	= '';

	/**
	 * Constructor
	 * @param object $Owner - object instance to which belong this object instance
	 * @param array $params
	 * @return void
	 */
	public function __construct( $Owner ){
		$this->mPaging	= $Owner->getPagingPrms();
		$this->mPgInfo	= $Owner->getPagingInfo();
		$this->mType	= 'button';
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	private function getOverCssClass( $bntName ){
		$class_name	= '';
		switch( $bntName ){
			case 'prv_gr': $class_name	= 'PPSK_tablePrevPagesOvr'; break;
			case 'prv_pg': $class_name	= 'PPSK_tablePrevPageOvr'; break;
			case 'nxt_pg': $class_name	= 'PPSK_tableNextPageOvr'; break;
			case 'nxt_gr': $class_name	= 'PPSK_tableNextPagesOvr'; break;
			default:  $class_name	= 'PPSK_tablePageNumberAct';

		}
		return $class_name;
	}
//______________________________________________________________________________

	private function getOutCssClass( $bntName ){
		$class_name	= '';
		switch( $bntName ){
			case 'prv_gr': $class_name	= 'PPSK_tablePrevPagesAct'; break;
			case 'prv_pg': $class_name	= 'PPSK_tablePrevPageAct'; break;
			case 'nxt_pg': $class_name	= 'PPSK_tableNextPageAct'; break;
			case 'nxt_gr': $class_name	= 'PPSK_tableNextPagesAct'; break;
			default:  $class_name	= 'PPSK_tablePageNumberAct';

		}
		return $class_name;
	}
//______________________________________________________________________________

	private function createPagingActions( $bntName, $page ){
		$resourse	= self::getHandleResourceString( 'onClickPgBtnHandler', get_class( $this->mOwner ) );
		$this->setHandler( array( 'handler'=>"xajax_onHandler(\"".$resourse."\", $page );" ), 'onclick' );
		$this->setHandler( array( 'handler'=>"mouseOverOut( this, \"".$this->getOverCssClass( $bntName )."\");" ), 'onmouseover' );
		$this->setHandler( array( 'handler'=>"mouseOverOut( this, \"".$this->getOutCssClass( $bntName )."\");" ), 'onmouseout' );
	}
//______________________________________________________________________________
	/**
	 * defines if paging button is active
	 * @param	string $bntName
	 * @param	integer &$toPage - referance
	 * @return	boolean and $toPage parameter as num of page
	 */
	private function isToPageAct( $bntName, &$toPage ){
		$info			= &$this->mPgInfo;
		switch( $bntName ){
			case 'prv_pg':
				$toPage	= $info[ 'page' ] - 1;
				$is_act		= !( $toPage < PTable::_fstPg );
				break;

			case 'prv_gr':
				$toPage	= $info[ 'grp_start' ] - 1;
				$is_act		= !( $toPage < PTable::_fstPg );
				break;

			case 'nxt_pg':
				$toPage	= $info[ 'page' ] + 1;
				$is_act		= !( $toPage > $info[ 'max_page' ] );
				break;

			case 'nxt_gr':
				$toPage	= $info[ 'grp_start' ] + $info[ 'max_gr_pg' ];
				$is_act		= !( $toPage > $info[ 'max_page' ] );
				break;

			case 'num_pg':
				$is_act		= ( $toPage != $info[ 'page' ] );
				break;

			default:
				$is_act	= FALSE;
		}
		$this->createPagingActions( $bntName, $toPage );
		return $is_act;
	}
//______________________________________________________________________________

	public function getHtmlView(){
		$num		= $this->mNum;
		$bntName	= $this->mBntName;
		$this->mName= $bntName.$num;

		$paging_prms	= &$this->mPaging;

		$this->mCssAct	= $paging_prms[ $bntName.'_img_act' ];
		$this->mCssDis	= $paging_prms[ $bntName.'_img_dis' ];
		$this->setPrompt( $num );

		$is_to_page_act	= $this->isToPageAct( $bntName, $num );
		if( $is_to_page_act ){
			$hint	= $paging_prms[ $bntName.'_hint' ];
			$this->setEnabled();
		}else{
			$hint	= '';
			$this->setDisabled();
		}
		$this->setHint( $hint );
		return parent::getHtmlView();
	}
//______________________________________________________________________________


	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________
}//	Class end
