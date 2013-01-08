<?php


class PDbl extends Core{ //
	const _duplicateEntry	= 1062;
	const _cannotDelUpdate	= 1451;

	public function __construct( $Owner = NULL ){
		parent::__construct( $Owner );
	}
	//--------------------------------------------------------------------------------------------------
	function getLogErrorMessage( $sqlString, $resource='Undefined' ){
		global $gl_MysqliObj;
		return
			_EX.'MySQL error: '.$gl_MysqliObj->errno.' << '.$gl_MysqliObj->error.' >>. Resource: "'.$resource.'". '.
			"\nThe whole SQL query is:\n".$sqlString;
	}
//--------------------------------------------------------------------------------------------------

	public function execSelectQuery( $sql, $resource='Undefined' ){
		global $gl_MysqliObj;

		$result = $gl_MysqliObj->query( $sql );

		if( $result ){
			$list	= array();
			while( $row = $result->fetch_assoc() ){
				$list[] = $row;
			}
			$result->close();

		}else{
			throw new Exception( $this->getLogErrorMessage( $sql, $resource ) );
		}

		return $list;
	}
	//--------------------------------------------------------------------------------------------------

	private function getDuplicateEntryParams( $errDescr ){
		$err_params	= explode( "'", $errDescr );
		return array( 'field' => $err_params[ 3 ], 'value' => $err_params[ 1 ] );
	}
	//--------------------------------------------------------------------------------------------------

	private function parserError( $sql = '' ){	//	1064 Syncas error
		global $gl_MysqliObj;
		$data	= &$this->mOwner->mSaveData;

		$err_no	= $gl_MysqliObj->errno;
		$err_dscr	= $gl_MysqliObj->error;

		switch( $err_no ){
			case  self::_duplicateEntry:
				$res	= self::getDuplicateEntryParams( $err_dscr );

				foreach( $data as $items ){
					if( $items[0] == trim( $res['field'] )){ break; }
				}

				$res['description']	= sprintf( _PPSK_DB_ERR_DUBLICATE_ENTRY, $res[ 'value' ], $items[ 3 ] );
				$res['focus_id']	= $items[2];
				break;

			case  self::_cannotDelUpdate:
				$res[ 'description' ]	= _PPSK_DB_ERR_FOREIGN_KEY_CONSTRAINT;
				$res[ 'focus_id' ]		= _EMPTY;
				break;

			default:
				//				$res[ 'description' ]	= _MESSAGE_DB_ERROR;

				$res['description']	= 'Err num: '.$err_no." //// Descr: ".$err_dscr."\nThe whole query is:\n".$sql;
				$res['focus_id']		= '';
		}
		$res['is_error']= TRUE;
		$res['id']		= NULL;
		return $res;
	}
	//--------------------------------------------------------------------------------------------------

	public function updateRow(){
		$data	= &$this->mOwner->mSaveData;
		$sql = "UPDATE `".$this->mOwner->getTargetDbTable()."` SET ";

		$id	= 'NULL';
		foreach( $data as $items ){
			( $items[ 0 ] != 'id' ) ? $sql .=  "`".$items[ 0 ]."` = '".$items[ 1 ]."'," : $id = $items[ 1 ];
		}
		$len	= strlen( $sql ) - 1;
		$sql	= substr( $sql, 0, $len );
		$sql	.= " WHERE `id` = ".$id;

		return $this->execQuery( $sql );
	}
	//--------------------------------------------------------------------------------------------------

	public function addRow(){
		$data	= &$this->mOwner->mSaveData;

		$sql	="INSERT INTO `".$this->mOwner->getTargetDbTable()."` (";
		foreach( $data as $items ){
			$sql .=  "`".$items[ 0 ]."`,";
		}
		$len	= strlen( $sql ) - 1;
		$sql	= substr( $sql, 0, $len );

		$sql	.= ")VALUES(";
		foreach( $data as $items ){
			$sql .=  ( !$items[ 1 ] ) ? "NULL," : "'".$items[ 1 ]."',";
		}
		$len	= strlen( $sql ) - 1;
		$sql	= substr( $sql, 0, $len );
		$sql	.= ")";

		return $this->execQuery( $sql );
	}
	//--------------------------------------------------------------------------------------------------

	public function execQuery( $sql ){
		global $gl_MysqliObj;
		$res = $gl_MysqliObj->query( $sql );

		if( !$res ){
			$result	= $this->parserError( $sql );
		}else{
			$result	= array( 'is_error' => FALSE, 'id' => $gl_MysqliObj->insert_id );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------------------------

	public function deleteRow( $table, $recId ){
		$sql	= "DELETE FROM `".$table."` WHERE `".$table."`.`id` = ".$recId;
		return $this->execQuery( $sql );
	}
	//--------------------------------------------------------------------------------------------------

	function getUserInfoById( $userId, $dbTable = '' ){
		if( $dbTable == '' ){
			throw new Exception( _EX.'Table name is empty. Line: '.__LINE__.' in '.__FILE__.'.' );
		}


		$user_id	= ( !$userId ) ? 0 : $userId;
		global $gl_MysqliObj;
		$sql	=
	"SELECT ".
		"`".$dbTable."`.`id` as `id`, ".
// 		'`level`, '.
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
	FROM `".$dbTable."`
	LEFT JOIN `cities` ON `city_id` = `cities`.`id`
	LEFT JOIN `countries` ON `countries`.`id` = `cities`.`country_id`
	WHERE `".$dbTable."`.`id`=".$user_id;


		$result = $gl_MysqliObj->query( $sql );
		if( $result ){
			$row = $result->fetch_assoc();
			$result->close();
		}else{
			throw new Exception( _EX.'Bad MySQL result. On line: '.__LINE__.' in '.__FILE__.".\nThe whole SQL query is: \n".$sql );
		}

		$row = ( !$row ) ? array(
			'id'			=> NULL,
//  			'level'			=> NULL,
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
	//--------------------------------------------------------------------------------------------------

	public function __destruct(){
		parent::__destruct ();
	}
	//--------------------------------------------------------------------------------------------------

}// Class end
