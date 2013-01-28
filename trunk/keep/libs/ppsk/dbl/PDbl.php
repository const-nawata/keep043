<?php
class PDbl extends Core{ //
	const _duplicateEntry	= 1062;
	const _cannotDelUpdate	= 1451;

	public function __construct( $Owner = NULL ){
		parent::__construct( $Owner );
	}
//______________________________________________________________________________

	protected function parserError( $sql = '' ){	//	1064 Syncas error
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

				$res['description']	= 'DB Error. Number: '.$err_no.' / Description: '.$err_dscr." / The whole query is:\n".$sql;
				$res['focus_id']		= '';
		}
		$res['is_error']= TRUE;
		$res['id']		= NULL;
		$res['type']	= 'db_error';
		return $res;
	}
//______________________________________________________________________________

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
			$error	= $this->parserError( $sql );
//TODO: If it is necessary Exception throwing. May be there is better solution.
			throw new Exception( 'Resource: '.$resource.'. '.$error['description'] );
		}

		return $list;
	}
//______________________________________________________________________________

	private function getDuplicateEntryParams( $errDescr ){
		$err_params	= explode( "'", $errDescr );
		return array( 'field' => $err_params[ 3 ], 'value' => $err_params[ 1 ] );
	}
//______________________________________________________________________________

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
//______________________________________________________________________________

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
//______________________________________________________________________________

	public function getRow(){
	}
//______________________________________________________________________________

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
//______________________________________________________________________________

	public function deleteRow( $id ){
		$table	= $this->mOwner->__get( 'mTargetDbTable' );
		$sql	= 'DELETE FROM `'.$table.'` WHERE `'.$table.'`.`id`='.$id;
		return $this->execQuery( $sql );
	}
//______________________________________________________________________________

	public function __destruct(){
		parent::__destruct ();
	}
//______________________________________________________________________________

}// Class end
