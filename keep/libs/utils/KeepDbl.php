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
		$files_default	= array(
		0=>		array( 'name' => 'as0000000001.png',	'width' => 200, 'height' => 200 ),
		1=>		array( 'name' => 'as0000000001_1.png',	'width' => 200, 'height' => 200 ),
		2=>		array( 'name' => 'as0000000001_2.png',	'width' => 200, 'height' => 200 ),
		3=>		array( 'name' => 'as0000000002.png',	'width' => 200, 'height' => 200 ),
		4=>		array( 'name' => 'as0000000002_1.png',	'width' => 200, 'height' => 200 ),
		5=>		array( 'name' => 'as0000000002_2.png',	'width' => 200, 'height' => 200 ),
		6=>		array( 'name' => 'as0000000003.png',	'width' => 200, 'height' => 200 ),
		7=>		array( 'name' => 'as0000000003_1.png',	'width' => 200, 'height' => 200 ),
		8=>		array( 'name' => 'as0000000003_2.png',	'width' => 200, 'height' => 200 ),
		9=>		array( 'name' => 'as0000000004.png',	'width' => 200, 'height' => 200 ),
		10=>	array( 'name' => 'as0000000004_1.png',	'width' => 200, 'height' => 200 ),
		11=>	array( 'name' => 'as0000000004_2.png',	'width' => 200, 'height' => 200 )
		);

		$files	= array();
		$sql =
"SELECT `img_name` as `name`, `img_width` as `width`, `img_height` as `height` ".
"FROM `goods` WHERE `img_name` != '' ORDER BY RAND() LIMIT 15";

		$files		= $this->execSelectQuery( $sql );
		$n_elems	= count( $files );

		if( $n_elems > 0 ){
			$path	= "./img/assortment/";
		}else{
			$files	=  $files_default;
			$path	= "./img/assortment/default/";
		}

		$content	= array( 'files' => $files, 'path' => $path );

		return $content;
	}
//______________________________________________________________________________

	function getRunningMessage(){
		if( 0 ){
			$sql_string = "SELECT welcome FROM settings";
			$result = mysql_query($sql_string);
			$row = mysql_fetch_assoc($result);
			$content = $row['welcome'];
		}else{
			$content = "Welcome test message which was created by program.";
		}

		$content	= trim($content);
		return $content;
	}
//______________________________________________________________________________

	function getDepartmentInfo( $depId ){
		$dep_id	= ( !$depId ) ? 0 : $depId;
		global $gl_MysqliObj;
		$sql	=
'SELECT '.
	'`id`,'.
	'`name`,'.
	'`info` '.
'FROM `departments` '.
'WHERE `id`='.$dep_id;

		$result = $gl_MysqliObj->query( $sql );
		if( $result ){
			$row = $result->fetch_assoc();
			$result->close();
		}else{
			$res_err	= $this->parserError( $sql );
			throw new Exception( _EX.$res_err['description'] );
		}

		$row = ( !$row ) ? array(
			'id'			=> NULL,
			'name'			=> NULL,
			'info'			=> NULL
		) : $row ;
		return $row;
	}
//______________________________________________________________________________

	function geCategoryInfo( $catId ){
		$cat_id	= ( !$catId ) ? 0 : $catId;
		global $gl_MysqliObj;

		$sql	=
'SELECT '.
	'`id`,'.
	'`name`'.
'FROM `categories` '.
'WHERE `id`='.$cat_id;

		$result = $gl_MysqliObj->query( $sql );
		if( $result ){
			$row = $result->fetch_assoc();
			$result->close();
		}else{
			$res_err	= $this->parserError( $sql );
			throw new Exception( _EX.$res_err['description'] );
		}

// 		$row = ( !$row ) ? array(
// 			'id'			=> NULL,
// 			'name'			=> NULL
// 		) : $row ;
		return $row;
	}
//______________________________________________________________________________

	function getCountryInfoById( $countryId ){
		$country_id	= ( !$countryId ) ? 0 : $countryId;
		global $gl_MysqliObj;
		$sql	=
	"SELECT
		`countries`.`id` AS `id`,
		`countries`.`name` AS `name`
	FROM `countries`
	WHERE `countries`.`id`=".$country_id;

		$result = $gl_MysqliObj->query( $sql );
		if( $result ){
			$row = $result->fetch_assoc();
			$result->close();
		}else{
			throw new Exception( _EX."Bad MySQL result. Resource: getCountryInfoById in KeepDbl.php. The whole SQL query is: ".$sql );
		}

		$row = ( !$row ) ? array(
			'id'	=> NULL,
			'name'	=> NULL
		) : $row ;
		return $row;
	}
//______________________________________________________________________________

	function getUnitInfoById( $unitId ){
		$unit_id	= ( !$unitId ) ? 0 : $unitId;
		global $gl_MysqliObj;
		$sql	=
	"SELECT
		`units`.`id` AS `id`,
		`units`.`full_name` AS `full_name`,
		`units`.`brief_name` AS `brief_name`
	FROM `units`
	WHERE `units`.`id`=".$unit_id;

		$result = $gl_MysqliObj->query( $sql );
		if( $result ){
			$row = $result->fetch_assoc();
			$result->close();
		}else{
			throw new Exception( _EX."Bad MySQL result. Resource: getUnitInfoById in KeepDbl.php. The whole SQL query is: ".$sql );
		}

		$row = ( !$row ) ? array(
			'id'			=> NULL,
			'full_name'		=> NULL,
			'brief_name'	=> NULL
		) : $row ;
		return $row;
	}
//______________________________________________________________________________

	function deleteDbViewsForManager( $managerId ){
		$sql	=
"DROP VIEW IF EXISTS `clients_".$managerId."`";
		$result	= $this->execQuery( $sql );

		if( !$result[ 'is_error' ] ){
			$sql	=
"DROP VIEW IF EXISTS `departments_".$managerId."`";
			$result	= $this->execQuery( $sql );
		}
		return $result;
	}
//______________________________________________________________________________

	public function getUserInfoById( $userId ){
		$user_id	= ( !$userId ) ? 0 : $userId;

		global $gl_MysqliObj;

		$sql	=
	"SELECT ".
		"`users`.`id` as `id`, ".
		'`level`, '.
		"`firstname`,
		`surname`,
		`city_id`,
		`cities`.`name` AS `city`,
		`cities`.`country_id` AS `country_id`,
		`countries`.`name` AS `country`,
		`info`,
		`password`,
		`login`,
		`email`
	FROM `users`
	LEFT JOIN `cities` ON `city_id` = `cities`.`id`
	LEFT JOIN `countries` ON `countries`.`id` = `cities`.`country_id`
	WHERE `users`.`id`=".$user_id;


		$result = $gl_MysqliObj->query( $sql );
		if( $result ){
			$row = $result->fetch_assoc();
			$result->close();
		}else{
			throw new Exception( _EX.'Bad MySQL result. On line: '.__LINE__.' in '.__FILE__.".\nThe whole SQL query is: \n".$sql );
		}

		$row = ( !$row ) ? array(
			'id'			=> NULL,
 			'level'			=> NULL,
			'firstname'		=> NULL,
			'surname'		=> NULL,
			'city_id'		=> NULL,
			'city'			=> NULL,
			'country_id'	=> NULL,
			'country'		=> NULL,
			'info'			=> NULL,
			'password'		=> NULL,
			'login'			=> NULL,
			'email'			=> NULL
		) : $row ;
		return $row;
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

}// Class end
