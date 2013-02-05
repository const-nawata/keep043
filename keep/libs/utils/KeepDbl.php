<?php
class KeepDbl extends PDbl{

	public function __construct( $Owner = NULL ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	function getDuplicateEntryParams( $errDescr ){
		$string_pars	= explode( "'", $errDescr );
		return array( 'field' => $string_pars[ 3 ], 'value' => $string_pars[ 1 ] );
	}
//______________________________________________________________________________


	function getNews(){
		//    global $mIsMySqlConn;
		if( 0 ){
			$today	= ( 0 ) ? getTodayDate() : "11-12-2008";


			$db_min_date	= getDbDate( addDaysToDate( $today, -90 ) );
			$sql_string		= "SELECT DATE_FORMAT(date, '%d-%m-%y') as fdate, content FROM news WHERE date > '$db_min_date'  ORDER BY date DESC";
			$content = execSelectQuery($sql_string);
		}else{
			$content	= array();
			for( $i = 0; $i < 20; $i++ ){
				$content[$i]	= array('fdate'=>'17-10-08', 'content'=>$i.'#### This test content is switched on/off in PDbl.php file. See method getNews. #### This test content is switched on/off in PDbl.php file. See method getNews. #### This test content is switched on/off in PDbl.php file. See method getNews. #### This test content is switched on/off in PDbl.php file. See method getNews. #### This test content is switched on/off in PDbl.php file. See method getNews. #### This test content is switched on/off in PDbl.php file. See method getNews. #### This test content is switched on/off in PDbl.php file. See method getNews.  ');
			}
		}
		return $content;
	}
//______________________________________________________________________________

	function getSlides(){


		$files	= array();
		$sql =
'SELECT `img_file` as `file`, `img_width` as `width`, `img_height` as `height` '.
"FROM `goods` WHERE `img_file`!='' ORDER BY RAND() LIMIT 15";

		$files		= $this->execSelectQuery( $sql );


		return $content;
	}
//______________________________________________________________________________

	public  function getUserName( $id, $level ){
		$sql	=
"SELECT trim(concat_ws(' ',trim(`firstname`),trim(`surname`))) AS `user_name` ".
"FROM `users` WHERE `id`=".$id." AND `level`='".$level."'";

		$info	= $this->execSelectQuery( $sql, 'KeepDbl::getUserName' );

		return ( isset( $info[0] )) ? $info[0]['user_name'] : FALSE;
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

}// Class end
