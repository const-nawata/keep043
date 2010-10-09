<?php
class dbl{

	public static function getAnyAgendaForMonth( $month, $year, $appTypeId, $agId, $catId, $dtNow, $isTest, $orgCode ){
//	public function getAnyAgendaForMonth( $month, $year, $appTypeId, $agId, $catId, $today, $now, $orgCode )
//	public static function getAnyAgendaForMonth( $dtNow, $mon, $year, $appTypeId, $agIdsStr, $isTest ) ,
		global $CA_PATH, $gl_MysqliObj;

		$is_tst	= ( $isTest ) ? 'TRUE' : 'FALSE';

		$sql	=
//"CALL `get_any_agenda_month`( '".$dtNow."', ".$mon.", ".$year.", ".$appTypeId.", '".$agIdsStr."', ".$is_tst." )";
"CALL `get_any_agenda_month`( '".$dtNow."', ".$month.", ".$year.", ".$appTypeId.", ".$agId.", ".$catId.", '".$orgCode."', ".$is_tst." )";

		if( $gl_MysqliObj->multi_query( $sql ) ){
			while( $result = $gl_MysqliObj->use_result() ){
				while( $row = $result->fetch_array( MYSQLI_ASSOC ) ){
					$info[] = $row;
				}
				$result->close();
				$next_result	= $gl_MysqliObj->next_result();
			}
		}else{
			utils_ut::putLogInfo( utils_ut::getMysqlErrorInfo( $sql, 'dbl::getAnyAgendaForMonth' ), 'Error' );
			$info	= false;
		}
		return	$info;
	}
//--------------------------------------------------------------------------------------------------

}// Class end
?>