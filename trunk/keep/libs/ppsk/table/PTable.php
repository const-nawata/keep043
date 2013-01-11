<?php
require_once($gl_PpskPath."table/PagingButton.php");
require_once($gl_PpskPath."table/TableToolPane.php");
require_once($gl_PpskPath."table/TableSearchField.php");

/**
 * This class is used as inheritable class to create presentation of DB table as list of lines.
 * See doc.txt before use this class.
 *
 * You must create new class to use this class as parent and define properties of new class.
 *
 * After new class creation you must create new object instance and then call initHtmlView method to set
 * initial presentation of table HTML content.


 ---------------------------------------------------------
 *  * Example to create presentation list component:
 ---------------------------------------------------------

 class newClassName extends tblListView{


 // General Properties
 $this->mDbTableName = 'tableName';  //  Mandatory
 $this->mPgLength     = 7;                //  Optionlal. Default = 10
 $this->mMaxPgGroup     = 6;             //  Optionlal. Default = 5

 // Paging Properties
 $this->mHints = array(...);   //  Values are set from properties.php if those properties were not defined


 // Columns Properties
 $this->mColumns[] = array('table'=>'users',                                       // Mandatory
 'field'=>'surname',                                     // Mandatory
 'name'=>_USER_SURNAME,                     //  Optional. Default as in 'field' item..
 'ttl_css'=>'managersTblSurnameTtlTd',     //  Optional. Default = empty string.
 'clm_css'=>'managersTblSurnameClTd',    //  Optional. Default = empty string.
 'bg_color'=>'#CCFFCC');                          //  Optional. Default = '#FFFFFF'  (white)
 ...
 $this->mColumns[] ...


 public function __construct( $instName = NULL, $isHndl = false ){
 parent::__construct( $this );
 $this->initHtmlView( $isHndl );
 }
 }

 ---------------------------------------------------------
 *  * Example to create presentation HTML content
 ---------------------------------------------------------
 $managers_list_obj = new managersTblList();
 $list_view = $managers_list_obj->initView();
 ------------------------------------------------------------

 ---------------------------
 CSS styles descriptions.
 ---------------------------
 All default CSS definitions are presented in vcl.css file.
 ---------------------------

 --------------------
 General Remarks
 --------------------
 DB table which used to create list presentation must contain mandatory id field as autoincremental unique primary key.            ***                 IMPORTANT!!!
 This component was created and tested for MySQL version 5.0.26-community-nt; PHP version 5.2.1; Apache version 2.2.4.
 Every sell of table list which is information sell has tag id which has name 'td_'.classname.fieldname.row_id
 @author Constantine Nawata (nawataster@gmail.com)
 @version 1.0
 */
abstract class PTable extends Core{

	const _stdPgLen		= 10;
	const _stdPgGrpLen	= 5;
	const _gradValue	= 20;
	const _fstPg		= 1;
	//-----------------------//----------------------//----------------------//-----------------------//

	/**
	 * @property string $mSourceDbTable -  Table name which is used for SQL query. Mandatory.
	 * 		Must contain only one name of table.
	 */
	protected $mSourceDbTable	= _EMPTY;

	/**
	 * @property string $mSourceDbTable -  Table name which is used for SQL query. Mandatory.
	 * 		Must contain only one name of table.
	 */
	protected $mTargetDbTable	= _EMPTY;

	/**
	 * @property array $mColumns[] => array(	-Columns properties. Mandatory.
		[field]	-	field name in DB table.  Mandatory.

		[name] -	column name to show in presentation view. Optional. Default value = field value.

		[is_sort] -	false or true. This parameter is set if column must use sorting functionality. Optional.
		Default value = false.

		[ttl_css] -	CSS class name to set style properties for title column cell. Optional. Default = empty
		string. It is used to overlap CSS styles which are got from  .PPSK_tableColumnTitleCellTd
		CSS class. Avoid to use .PPSK_tableColumnTitleCellTd name in your CSS styles. This may affect
		other tables presentations. Don't use dot in ttl_css value.

		[sll_css] -	CSS class name to set style properties for info column sell. Optional. Default = empty string.
		It is used to overlap CSS styles which are got from  .PPSK_tableColumnInfoCellTd CSS class. Avoid
		to use .PPSK_tableColumnInfoCellTd name in your CSS styles. This may affect other tables
		presentations. Don't use dot as a part of clm_css value. Don't use background-color property
		in this class. Background-color property must be difined by bg_color index of this array. Such
		definition is assumed in order to create dark and light lines in list presentation.

		[bg_clr] -	background color for info column cell in format #NNNNNN. Optional. Default value = #FFFFFF (white).
		Don't use background-color property in CSS class which name is defined in sll_css value.
		)
		*/
	protected $mColumns		= array();

	/**
	 * @property string $mPgLen - Number of lines in page. Optional.
	 */
	protected $mPgLen		= self::_stdPgLen;

	/**
	 * @property integer $mMaxGrPg - max number of page items which must be shown in pagination. Optional.
	 */
	protected $mMaxGrPg	= self::_stdPgGrpLen;

	/**
	 * @filesource PTable.php
	 * @access	protected
	 * @property boolean $mIsFixHeight - defines if last page must be fixed height or no.
	 * Must be set to true value if height of last page is fixed one (equals to $mPgLen).
	 */
	protected $mIsFixHeight	= FALSE;

	/**
	 * @filesource PTable.php
	 * @access	protected
	 * @property boolean $mIsGrad  - difines if to show lines with differnt back-
	 * 		ground color. Optional.
	 */
	protected $mIsGrad	= TRUE;

	/**
	 * Color for line-selector in table view
	 * @filesource PTable.php
	 * @access	protected
	 * @property string $mSelectorColor - Color value in format #NNNNNN. Optional.
	 * 		If value == _EMPTY then cursor line isn't shown and no action for line
	 * 		onclick event is perfomed. This property also defines if to show tool
	 * 		bar for create, edit, delete records.
	 */
	protected $mSelectorColor	= _EMPTY;

	/**
	 * @property array $mPaging  - CSS styles for paging.
	 Array (
	 [css_panel]  - CSS class to present paging panel. Optional.
		[num_pg_img_act]  - CSS class to present active page number. Optional.
		[num_pg_imp_dis]  - CSS class to present non active page number. Optional.
		[css_info] - CSS class to present info line. Optional.

		[num_pg_img_act] - CSS class to present image button of number page. Optional.
		[num_pg_img_dis] - CSS class to present image button of number page when it disabled. Optional.
		[num_pg_img_ovr] - CSS class to present image button of number page when mouse over it. Optional.
		[num_pg_hint] - hint for the button of number page. Optional.

		[prv_pg_img_act] - CSS class to present image button of previous page when it enabled. Optional.
		[prv_pg_img_dis] - CSS class to present image button of previous page when it disabled. Optional.
		[prv_pg_img_ovr] - CSS class to present image button of previous page when mouse over it. Optional.
		[prv_pg_hint] - hint for the button of previous page. Optional.

		[prv_gr_img_act] - CSS class to present image button of group of previous pages when it enabled. Optional.
		[prv_gr_img_dis] - CSS class to present image button of group of previous pages when it disabled. Optional.
		[prv_gr_img_ovr] - CSS class to present image button of group of previous pages when mouse over it. Optional.
		[prv_gr_hint] - hint for the button of previous group of pages. Optional.

		[nxt_pg_img_act] - CSS class to present image button of next page when it enabled. Optional.
		[nxt_pg_img_dis] - CSS class to present image button of next page when it disabled. Optional.
		[nxt_page_img_ovr] - CSS class to present image button of next page when mouse over it. Optional.
		[nxt_pg_hint] - hint for the button of next page. Optional.

		[nxt_gr_img_act] - CSS class to present image button of group of next pages when it enabled. Optional.
		[nxt_gr_img_dis] - CSS class to present image button of group of next pages when it disabled. Optional.
		[nxt_gr_img_ovr] - CSS class to present image button of group of next pages when mouse over it. Optional.
		[nxt_gr_hint] - hint for the button of next group of pages. Optional.
		)

		*/
	protected $mPaging		= array(
		'css_panel'			=> _EMPTY,
		'css_info'			=> _EMPTY,
		'css_btn'			=> _EMPTY,

		'num_pg_img_act'	=> _EMPTY,
		'num_pg_img_dis'	=> _EMPTY,
		'num_pg_img_ovr'	=> _EMPTY,
		'num_pg_hint'		=> _EMPTY,

		'prv_pg_img_act'	=>'PPSK_tablePrevPageAct',
		'prv_pg_img_dis'	=>'PPSK_tablePrevPageDis',
		'prv_pg_img_ovr'	=>'',
		'prv_pg_hint'		=>_PPSK_HINT_PREV_PAGE,

		'prv_gr_img_act'	=>'PPSK_tablePrevPagesAct',
		'prv_gr_img_dis'	=>'PPSK_tablePrevPagesDis',
		'prv_gr_img_ovr'	=>'',
		'prv_gr_hint'		=>_PPSK_HINT_PREV_PAGES,

		'nxt_pg_img_act'	=>'PPSK_tableNextPageAct',
		'nxt_pg_img_dis'	=>'PPSK_tableNextPageDis',
		'nxt_pg_img_ovr'	=>'',
		'nxt_pg_hint'		=>_PPSK_HINT_NEXT_PAGE,

		'nxt_gr_img_act'	=>'PPSK_tableNextPagesAct',
		'nxt_gr_img_dis'	=>'PPSK_tableNextPagesDis',
		'nxt_gr_img_ovr'	=>'',
		'nxt_gr_hint'		=>_PPSK_HINT_NEXT_PAGES,

		'emp_pg_img_act'	=>_EMPTY,
		'emp_pg_img_dis'	=>_EMPTY,
		'emp_pg_img_ovr'	=>_EMPTY,
		'emp_pg_hint'		=>_EMPTY/*,

	'emp_gr_img_act'	=>_EMPTY,
	'emp_gr_img_dis'	=>_EMPTY,
	'emp_gr_img_ovr'	=>_EMPTY,
	'emp_gr_hint'		=>_EMPTY*/
	);

	/**
	 * @property object $mToolPaneObj - Table tool pane
	 * @
	 */
	public $mToolPaneObj;

	/**
	 * @property arraly $mToolPaneButtons
	 */
	public $mToolPaneButtons;

	/**
	 * @property object $mSearchInputObj - Table search field
	 */
	public $mSearchInputObj;

	/**
	 * Parameters for searching
	 * @access	protected
	 * @property array $mSearchParams
	 */
	private $mSearchParams	= array(
		'fields'	=> array(),
		'buttons'	=> array(
			'search'	=> array(
				'hint'	=> _PPSK_HINT_SEARCH
			),

			'clear'		=> array(
				'hint'	=> _PPSK_HINT_SEARCH_CLEAR
			)
		)
	);

	/**
	 * Table line which is set above title sells.
	 * @access	protected
	 * @property string $mUpperLine
	 */
	protected $mUpperLine	= '';

	/**
	 * Access levels
	 * @access	protected
	 * @property array $mLevels
	 */
	protected $mLevels	= array();

	/**
	 * Name of class of pane for add / edit
	 * @access	public
	 * @property string $mPaneClassName
	 */
	public $mPaneClassName	= _EMPTY;

	//-----------------//----------------//----------------//-----------------//
	private	$mInfo;
	private $mGradFlg	= TRUE;
	//-----------------//----------------//----------------//-----------------//Methods

	public function __construct( $Owner ){
		global $gl_PpskPath;
		$auth_obj = new Authentication();
		if( $auth_obj->isGrantAccess( $this->mLevels ) ){
			$this->adjustProperties();
			$this->initSessionInfo();
			parent::__construct( $Owner );
			$this->mToolPaneObj	= new TableToolPane( $this );
			$class	= get_class( $this );
			$this->mSearchInputObj	= new TableSearchField( $this, $_SESSION['tables'][$class]['filter'] );
		}else{
			header( 'Location: '.$gl_PpskPath.'access.php' );
		}
	}
//______________________________________________________________________________

	/**
	 * sets HTML view
	 * @access public
	 * @param boolean $isHndl
	 * @return void
	 */
	public function initHtmlView( $isHndl ){
		$paging	= &$this->mPaging;
		$this->prepareData();
		$class	= get_class( $this );

		$view	= ( ( !$isHndl ) ? "
			<div id='".$class."_container'>" : "" ).
				"<table class='PPSK_tableTbl' cellpadding='0' cellspacing='0'>".
		$this->mUpperLine.$this->buildColumnsHtmlContent().
		$this->buildLinesHtmlContent().
					"<tr>
						<td colspan='".count( $this->mColumns )."' class='".$paging[ 'css_panel' ]."'>".
		$this->buildPagingHtmlContent().
						"</td>
					</tr>
				</table>".( ( !$isHndl ) ?
			"</div>":'');

		parent::initHtmlView( $view );
	}
//______________________________________________________________________________

	public function getAccess(){
		return $this->mLevels;
	}
//______________________________________________________________________________

	public function getPagingInfo(){
		return $this->mInfo;
	}
//______________________________________________________________________________

	public function getPagingPrms(){
		return $this->mPaging;
	}
//______________________________________________________________________________

	private function adjustPagingParams(){
		$paging	= &$this->mPaging;
		$paging[ 'css_panel' ]		= 'PPSK_tablePagingPanelTd '.$paging[ 'css_panel' ];
		$paging[ 'css_info' ]		= 'PPSK_tablePagingInfoTd '.$paging[ 'css_info' ];
		$paging[ 'css_btn' ]		= 'PPSK_tablePagingBtnTd '.$paging[ 'css_btn' ];

		$paging[ 'num_pg_img_act' ]	= 'PPSK_tablePageNumberAct '.$paging[ 'num_pg_img_act' ];
		$paging[ 'num_pg_img_dis' ]	= 'PPSK_tablePageNumberDis '.$paging[ 'num_pg_img_dis' ];
		$paging[ 'num_pg_img_ovr' ]	= 'PPSK_lstPageNumberOvr '.$paging[ 'num_pg_img_dis' ];

		$paging[ 'emp_pg_img_act' ]	= 'PPSK_tablePageEmptyDis '.$paging[ 'emp_pg_img_act' ];
		$paging[ 'emp_pg_img_dis' ]	= 'PPSK_tablePageEmptyDis '.$paging[ 'emp_pg_img_dis' ];
		$paging[ 'emp_pg_img_ovr' ]	= 'PPSK_tablePageEmptyDis '.$paging[ 'emp_pg_img_ovr' ];
	}
//______________________________________________________________________________

	private function adjustToolPaneButtonsParams(){
		$this->mToolPaneButtons	= array(
			'add'	=> array(
				'hint'	=> _PPSK_HINT_ADD_ROW,
    			'handlers'	=> array(
    				'onclick'	=> array(
    					'handler'	=> "xajax_onHandler(\"".$this->getHandleResourceString( 'addRowHandler', get_class( $this ) )."\", null );"
    					)
    					)
    					),
			'edit'	=> array(
				'hint'	=> _PPSK_HINT_EDIT_ROW,
    			'handlers'	=> array(
    				'onclick'	=> array(
    					'handler'	=> "xajax_onHandler(\"".$this->getHandleResourceString( 'editRowHandler', get_class( $this ) )."\", null );"
    					)
    					)
    					),


			'delete'	=> array(
				'hint'	=> _PPSK_HINT_DELETE_ROW,
    			'handlers'	=> array(
    				'onclick'	=> array(
    					'handler'	=> "xajax_onHandler(\"".$this->getHandleResourceString( 'showConfirmHandler', get_class( $this ) )."\", ".
    									"{ \"message\" : \""._MESSAGE_IS_DEL_RECORD."\", \"action\" : \"".self::encipherFilledValue( 'deleteRowHandler' )."\"} );"
    									)
    									)
    									)

    									//			'delete'	=> array(
    									//				'hint'	=> _PPSK_HINT_DELETE_ROW,
    									//    			'handlers'	=> array(
    									//    				'onclick'	=> array(
    									//    					'handler'	=> "xajax_onHandler(\"".$this->getHandleResourceString( 'deleteRowHandler', get_class( $this ) )."\", '1' );",
    									//    					'ask'	=> _MESSAGE_IS_DEL_RECORD
    									//    				)
    									//    			)
    									//    		)





    									);
	}
//______________________________________________________________________________

	private function adjustColumnsParams(){
		$columns	= &$this->mColumns;
		//    	$inst_name	= &$this->mName;
		$class = get_class( $this );

		if( count( $columns ) == 0 ){
			throw new Exception( _EX."Columns array is impty for object instance: ".$class );
		}
		foreach( $columns as &$column ){
			if( !isset( $column[ 'field' ] ) || $column[ 'field' ] == _EMPTY ){
				throw new Exception( _EX."Bad column field definition for object instance: ".$class."" );
			}
			$column[ 'name' ]		= ( !isset( $column[ 'name' ] ) || $column['name'] == _EMPTY ) ? $column[ 'field' ] : $column[ 'name' ];
			$column[ 'is_sort' ]	= ( !isset( $column[ 'is_sort' ] ) ) ? false : $column[ 'is_sort' ];
			$column[ 'bg_clr' ]		= ( !isset( $column[ 'bg_clr' ] ) || $column[ 'bg_clr' ] == _EMPTY ) ?  _PPSK_WHITE_COLOR : $column[ 'bg_clr' ];
			$column[ 'ttl_css' ]	= ( !isset( $column[ 'ttl_css' ] ) ) ? 'PPSK_tableColumnTitleCellTd' : 'PPSK_tableColumnTitleCellTd '.$column[ 'ttl_css' ];
			$column[ 'sll_css' ]	= ( !isset( $column[ 'sll_css' ] ) ) ? 'PPSK_tableColumnInfoCellTd' :' PPSK_tableColumnInfoCellTd '.$column[ 'sll_css' ];
			$column[ 'grd_clr' ]	= $this->adjustLineColor( $column[ 'bg_clr' ] );
		}
	}
//______________________________________________________________________________

	private function adjustSearchParams(){
		$class = get_class( $this );
		$search	= &$this->mSearchParams;
		$search[ 'buttons' ][ 'search' ][ 'handlers' ]	= array(
    			'onclick'	=> array(
    				'handler'	=> "xajax_onHandler(\"".$this->getHandleResourceString( 'searchByFilterHandler', get_class( $this ) )."\", document.getElementById(\"inp_".$class."_TableSearchField\").value );"
    				)
    				);
        $search[ 'buttons' ][ 'clear' ][ 'handlers' ]	= array(
    			'onclick'	=> array(
    				'handler'	=> "xajax_onHandler(\"".$this->getHandleResourceString( 'searchByFilterHandler', get_class( $this ) )."\", \"\" ); "
    				)
    				);
	}
//______________________________________________________________________________

	private function adjustProperties(){
		$this->adjustPagingParams();
		$this->adjustColumnsParams();
		$this->adjustSearchParams();
		$this->adjustToolPaneButtonsParams();
		( _EMPTY == $this->mTargetDbTable ) ? $this->mTargetDbTable = $this->mSourceDbTable:'';
	}
//______________________________________________________________________________

	protected function setSearchFields( $fields ){
		$this->mSearchParams[ 'fields' ]	= $fields;
	}
//______________________________________________________________________________

	public function getFilterButtonParams( $btnType ){
		return $this->mSearchParams[ 'buttons' ][ $btnType ];
	}
//______________________________________________________________________________

	/**
	 * finds number of first page of groupe												Paging.
	 * @return void
	 */
	private function findStartPgOfGrp(){
		$page			= &$this->mInfo[ 'page' ];
		$curr_pg_ind	= $page - 1;
		$this->mInfo[ 'grp_start' ]	= $page - ( $curr_pg_ind % $this->mMaxGrPg );
	}
//______________________________________________________________________________

	/**
	 * creates HTML content for paging.
	 * @return string - HTML content.
	 */
	private function buildPagingHtmlContent(){
		$paging	= &$this->mPaging;
		$info	= &$this->mInfo;

		$string = "
<table class='PPSK_tablePagingTbl' cellpadding='0' cellspacing='0'>
	<tr>
		<td class='".$paging['css_info']."'>"._FOUND.": ".$info['n_all']."</td>";

		if( $info[ 'n_all' ] > $this->mPgLen ){
			$pg_obj	= new PagingButton( $this );

			$string .= "
		<td class='".$paging['css_btn']."'>".$pg_obj->getHtmlView( 'prv_gr' )."</td>
		<td class='".$paging['css_btn']."'>".$pg_obj->getHtmlView( 'prv_pg' )."</td>";

			$max_grp_pg	= $max_grp_pg_valid = $info[ 'grp_start' ] + $this->mMaxGrPg;

			$max_page	= $this->mInfo[ 'max_page' ] + 1;
			$max_grp_pg	= ( $max_grp_pg > $max_page ) ?  $max_page : $max_grp_pg;

			for( $n_page = $info[ 'grp_start' ]; $n_page < $max_grp_pg; $n_page++ ){
				$string .= "<td class='".$paging['css_btn']."'>".$pg_obj->getHtmlView( 'num_pg', $n_page )."</td>";
			}

			for( $n_page; $n_page < $max_grp_pg_valid; $n_page++ ){
				$string .= "<td class='".$paging['css_btn']."'>&nbsp;</td>";
			}

			$string .= "
		<td class='".$paging['css_btn']."'>".$pg_obj->getHtmlView( 'nxt_pg' )."</td>
		<td class='".$paging['css_btn']."'>".$pg_obj->getHtmlView( 'nxt_gr' )."</td>";
		}
		$string .= "
	</tr>
</table>";

		return $string;
	}
//______________________________________________________________________________

	private function countAllRecs(){
		$n_recs	= &$this->mInfo[ 'n_all' ];
		$sql_cond	= $this->getSqlFilter();
		$sql		= "SELECT count( id ) AS count FROM ".$this->mSourceDbTable.$sql_cond;

		global $gl_MysqliObj;
		$result = $gl_MysqliObj->query( $sql );

		if( $result ){
			$n_recs	= $result->fetch_assoc();
			$n_recs	= $n_recs[ 'count' ];
			$result->close();
		}else{
			throw new Exception( _EX."Bad MySQL result. Resource: PTable::countAllRecs. The whole SQL query is: ".$sql );
		}
	}
//______________________________________________________________________________

	private function preparePagingData(){
		$pg_len		= &$this->mPgLen;

		$n_recs		= &$this->mInfo[ 'n_all' ];
		$max_page	= &$this->mInfo[ 'max_page' ];

		$class = get_class( $this );

		$sess_page	= &$_SESSION[ 'tables' ][ $class ][ 'page' ];
		$sess_page	= ( $sess_page < self::_fstPg ) ? self::_fstPg : $sess_page;

		$this->countAllRecs();

		$n_pages	= ceil( $n_recs / $pg_len );
		$n_pages	= ( !$n_pages ) ? 1 : $n_pages;
		( $n_pages < $this->mMaxGrPg ) ? $this->mMaxGrPg = $n_pages:'';

		$res		= $n_recs % $pg_len;
		$max_page	= ( $n_recs + ( $pg_len - $res ) ) / $pg_len;
		( $res < 1 ) ? $max_page--:'';

		$max_page	= ( !$max_page ) ? self::_fstPg : $max_page;

		( $sess_page > $max_page ) ? $sess_page	= $max_page:'';

		$this->mInfo[ 'page' ]	= $sess_page;
		$this->findStartPgOfGrp();
	}
//______________________________________________________________________________

	private function addEmptyLines(){
		$page_recs	= &$this->mInfo[ 'recs' ];
		$p_recs		= count( $page_recs );
		for( $i = $p_recs; $i < $this->mPgLen; $i++ ){
			$page_recs[ $i ]	= array( 'id' => 0 );
			foreach( $this->mColumns as &$column ){
				$index	= &$column[ 'field' ];
				$page_recs[ $i ][  $index ]	= '&nbsp;';
			}
		}
	}
//______________________________________________________________________________

	private function getSqlFilter(){
		$class = get_class( $this );
		$filter	= &$_SESSION[ 'tables' ][ $class ][ 'filter' ];
		$flields	= &$this->mSearchParams[ 'fields' ];
		$sql_cond	= "";
		if( _EMPTY != $filter && count( $flields) > 0 ){
			$sql_cond	.= " WHERE (";

			foreach( $flields as &$field_name ){
				$sql_cond .= "(`".$field_name."` LIKE '%".$filter."%')OR";
			}
			$ln	= strlen( $sql_cond ) - 2;
			$sql_cond	= substr( $sql_cond, 0, $ln );
			$sql_cond	.= ")";
		}
		return $sql_cond;
	}
//______________________________________________________________________________

	private function readDataForPage(){
		$class = get_class( $this );
		$pg_len		= &$this->mPgLen;
		$sort		= &$_SESSION[ 'tables' ][ $class ][ 'sort' ];
		$start_rec	= ( $_SESSION[ 'tables' ][ $class ][ 'page' ] - 1 ) * $pg_len;

		$sql_cond	= $this->getSqlFilter();
		$sql	= "SELECT `id`, ";
		foreach( $this->mColumns as &$column ){ $sql .= "`".$column[ 'field' ]."`,"; }
		$lng	= strlen( $sql ) - 1;
		$sql	= substr( $sql, 0, $lng );

		$sql_cond .= ( NULL != $sort[ 'field' ] ) ? " ORDER BY `".$sort[ 'field' ]."` ".$sort[ 'dir' ]." " : '';

		$sql	.= " FROM `".$this->mSourceDbTable."` ".$sql_cond." LIMIT $start_rec, $pg_len";

		$db_obj	= new PDbl( $this );
		$this->mInfo[ 'recs' ]	= $db_obj->execSelectQuery( $sql );
	}
//______________________________________________________________________________

	private function prepareData(){
		$class = get_class( $this );
		$sess_ln_id	= &$_SESSION[ 'tables' ][ $class ][ 'line_id' ];

		$this->mInfo	= array( 'page'=>NULL, 'max_page'=>NULL, 'recs'=>NULL, 'n_all'=>NULL, 'grp_start'=>0, 'max_gr_pg'=> $this->mMaxGrPg );
		$this->preparePagingData();
		$this->readDataForPage();

		$sess_ln_id	= ( $sess_ln_id == NULL && count( $this->mInfo[ 'recs' ] ) > 0 ) ? $this->mInfo[ 'recs' ][ 0 ][ 'id' ] : $sess_ln_id;

		if( $this->mIsFixHeight ){ $this->addEmptyLines(); }
	}
//______________________________________________________________________________


	///////////////////////////////////////

	private function createColumnAction( $field ){
		$class = get_class( $this );
		$field		= self::encipherFilledValue( $field );
		$resourse	= $this->getHandleResourceString( 'onClickSortHandler', $class );
		return "xajax_onHandler(\"".$resourse."\", \"".$field."\" );";
	}
//______________________________________________________________________________

	private function renderFieldName( $column ){
		$class = get_class( $this );
		$sort	= &$_SESSION[ 'tables' ][ $class ][ 'sort' ];

		$col_name	= ( $column[ 'is_sort' ] )
		? "<a onclick='".$this->createColumnAction( $column[ 'field' ] )."'>".$column[ 'name' ]."</a>"
		: $column[ 'name' ];

		$mark = 'PPSK_noSort';
		if( $column[ 'field' ] == $sort[ 'field' ] ){
			switch( $sort[ 'dir' ] ){
				case 'asc':		$mark = 'PPSK_sortAsc'; break;
				case 'desc':	$mark = 'PPSK_sortDesc'; break;
			}
		}
		return array ( 'name'=> $col_name, 'mark'=> $mark );
	}
//______________________________________________________________________________

	private function buildColumnsHtmlContent(){
		$columns = &$this->mColumns;
		$view	= "";
		foreach ( $columns as &$column ){
			$data	= $this->renderFieldName( $column );
			$view .= "
	<td class='".$column[ 'ttl_css' ]."'>
		<table cellpadding='0' cellspacing='0' class='PPSK_tableColumnTitleCellTbl'>
			<tr>
				<td>".$data[ 'name' ]."</td>
				<td class='PPSK_sortMarkSell'><div class='".$data[ 'mark' ]."'>&nbsp;</div></td>
			</tr>
		</table>
	</td>";
		}
		$view	.= "
</tr>";

		return $view;
	}
//______________________________________________________________________________

	private function buildLineSellsHtmlContent( $line ){
		$n_fld = 0; $view = ""; $id = 0;
		foreach( $line as $ind => $field_content ){
			$field_content	= trim( $field_content );

			if( $ind == 'id' ){
				$id = $field_content;
			}elseif( $ind != 'tr_id' ){
				$column	= &$this->mColumns[ $n_fld - 1 ];
				$class = get_class( $this );

				if( $id == $_SESSION[ 'tables' ][ $class ][ 'line_id' ] && $this->mSelectorColor != _EMPTY ){
					$bg_color	= $this->mSelectorColor;
				}else{
					$bg_color	= ( !( $this->mGradFlg && $id ) )
					? $column[ 'grd_clr' ]
					: $column[ 'bg_clr' ];
				}
				$cursor_style	= ( !$id ) ? "cursor: default;" : "cursor: pointer;";
				$style = "background-color:".$bg_color."; ".$cursor_style;

				$id_h	= $ind.':'.$line[ 'tr_id' ];
				if( _PPSK_IS_CIPHER ){
					$cipher_obj	= new sipherManager( $_SESSION[ 'cipher_base' ], $_SESSION[ 'cipher_key' ] );
					$id_h		= $cipher_obj->encipherString( $id_h );
				}

				$view .= "<td id='td_".$id_h."'><div id='dv_".$id_h."' class='".$column['sll_css']."' style='".$style."'>".$field_content."</div></td>";
			}
			$n_fld++;
		}
		return $view;
	}
//______________________________________________________________________________

	private function buildLinesHtmlContent(){
		$lines	= &$this->mInfo[ 'recs' ];
		$view	= "";
		foreach( $lines as $line ){
			$evt_out = $evt_over = $evt_onclick = $id_h = $line[ 'tr_id' ] = _EMPTY;
			if( $line[ 'id' ] && $this->mSelectorColor != _EMPTY ){
				$evt_over	= " onmouseover='PPSK_tblLineOver( this );' ";
				$evt_out	= " onmouseout='PPSK_tblLineOut( this );' ";

				$class = get_class( $this );
				$id_h		= self::encipherFilledValue( $line[ 'id' ] );
				$line[ 'tr_id' ]	= $id_h;
				$id_h	= " id='TrId_".$id_h."' ";

				$resourse	= $this->getHandleResourceString( 'onClickSelLineHandler', $class );
				$evt_onclick	= " onclick='xajax_onHandler( \"".$resourse."\", this.id );' ";
			}
			$view .= "<tr".$id_h.$evt_onclick.$evt_over.$evt_out.">".$this->buildLineSellsHtmlContent( $line )."</tr>";
			$this->mGradFlg = !$this->mGradFlg;
		}
		return $view;
	}
//______________________________________________________________________________

	/**
	 * calculates color value due to gradient flag
	 * @param string $color color in format #NNNNNN
	 * @return string $color color in format #NNNNNN
	 */
	private function adjustLineColor( $color ){
		$grd_obj	= new gradientManager();

		return ( $this->mIsGrad )
		? $grd_obj->getGradientedColor( $color )
		: $color;
	}
//______________________________________________________________________________

	/**
	 * sets session info
	 * @return void
	 */
	protected function initSessionInfo(){
		$class = get_class( $this );
		if( !isset( $_SESSION[ 'tables' ][ $class ] ) ){
			$_SESSION[ 'tables' ][ $class ] = array(
				'page'		=> self::_fstPg,
				'sort'		=> array( 'field' => NULL, 'dir' => 'asc' ),
				'line_id'	=> NULL,
				'filter'	=> _EMPTY
			);
		}
	}
//______________________________________________________________________________

	protected function setPAddEditPane( &$objResponse, $recId ){
		$auth_obj = new Authentication();
		if( $auth_obj->isGrantAccess( $this->mLevels ) ){
			$info_pane_obj	= new $this->mPaneClassName( $this );
			$info_pane_obj->setRecId( $recId );
			$info_pane_obj->initHtmlView();
			$info_pane_html	= $info_pane_obj->getHtmlView();

			$objResponse->prepend( 'body_id', 'innerHTML', "<div id='veil' class='PPSK_vail_div'></div>" );
			$objResponse->prepend( 'body_id', 'innerHTML', "<div id='pane_container' class='PPSK_pane_container_div'>".$info_pane_html."</div>" );

			$class = get_class( $this );
			$objResponse->assign(  'inp_'.$class."_TableSearchField", 'value', $_SESSION[ 'tables' ][ $class ][ 'filter' ] );	//	This necessary for Firefox!!!
			if( _EMPTY != $info_pane_obj->mInitFocus ){
				$objResponse->script( "setFocus( '".$info_pane_obj->mInitFocus."' );" );
			}

		}else{
			$objResponse = $this->doAccessDenied();
		}


	}
//______________________________________________________________________________

	public function getSourceDbTable(){
		return $this->mSourceDbTable;
	}
//______________________________________________________________________________

	public function getTargetDbTable(){
		return $this->mTargetDbTable;
	}
//______________________________________________________________________________

	//	Handlers	<><><><><><><><><><><><><><><><><><><><><><><><><><><><><><>

	public function addRowHandler( &$objResponse, $nullValue ){
		$this->setPAddEditPane( $objResponse, NULL );
	}
//______________________________________________________________________________

	public function editRowHandler( &$objResponse, $nullValue ){
		$class	= get_class( $this );
		$this->setPAddEditPane( $objResponse, $_SESSION[ 'tables' ][ $class ][ 'line_id' ] );
	}
//______________________________________________________________________________

	public function onClickSortHandler( &$objResponse, $field ){
		$field	= self::decipherFilledValue( $field );
		$class	= get_class( $this );
		$sort	= &$_SESSION['tables'][$class]['sort'];
		if(	$sort['field'] != $field ){
			$sort['field']	= $field;
			$sort['dir']		= 'asc';
		}else{
			$sort['dir']	= ( $sort[ 'dir' ] == 'asc' ) ? 'desc' : 'asc';
		}
		$tbl_obj	= new $class( NULL, TRUE );
		$objResponse->assign( $class.'_container', 'innerHTML', $tbl_obj->getHtmlView() );
	}
//______________________________________________________________________________

	public function onClickSelLineHandler( &$objResponse, $trId ){
		list( $prefix, $id )	= explode( '_', $trId );
		$id	= self::decipherFilledValue( $id );
		$class	= get_class( $this );
		$_SESSION['tables'][$class]['line_id']	= $id;
		$tbl_obj	= new $class( NULL, true );
		$objResponse->assign(  $class.'_container', 'innerHTML', $tbl_obj->getHtmlView() );
	}
//______________________________________________________________________________

	public function onClickPgBtnHandler( &$objResponse, $page ){
		$class	= get_class( $this );
		$_SESSION['tables'][$class]['page']	= $page;
		$tbl_obj	= new $class( NULL, true );
		$objResponse->assign( $class.'_container', 'innerHTML', $tbl_obj->getHtmlView() );
	}
//______________________________________________________________________________

	public function searchByFilterHandler( &$objResponse, $filterValue ){
		$class	= get_class( $this );

		$sess	= &$_SESSION[ 'tables' ][ $class ];
		$sess[ 'page' ]		= self::_fstPg;
		$sess[ 'line_id' ]	= NULL;
		$sess[ 'filter' ]	= trim( $filterValue );

		$tbl_obj	= new $class( NULL, true );

		$objResponse->assign( $class."_container", 'innerHTML', $tbl_obj->getHtmlView() );
		$objResponse->assign( 'inp_'.$class."_TableSearchField", 'value', $sess[ 'filter' ] );
	}
//______________________________________________________________________________

	public function deleteRowHandler( &$objResponse, $nullValue ){
		$edit_pane_obj	= new $this->mPaneClassName( $this );
		$table_name = $edit_pane_obj->getTargetDbTable();

		$auth_obj = new Authentication();
		if( $auth_obj->isGrantAccess( $this->mLevels ) ){
			$class		= get_class( $this );

			$db_obj	= new PDbl( $this );
			$result	= $db_obj->deleteRow( $table_name, $_SESSION[ 'tables' ][ $class ][ 'line_id' ] );

			if( $result[ 'is_error' ] ){
				$this->showAlertHandler( $objResponse, array( 'message' => $result[ 'description' ], 'focus' => $result[ 'focus_id' ] ) );
			}else{
				$tbl_obj	= new $class( NULL, true );
				$objResponse->assign( $class."_container", 'innerHTML', $tbl_obj->getHtmlView() );
			}
		}else{
			$objResponse = $this->doAccessDenied();
		}
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct();
	}
//______________________________________________________________________________

}//	Class end