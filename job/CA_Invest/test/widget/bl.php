<?php
require_once( $CA_PATH.'test/widget/dbl.php' );

class bl{



//TODO: Set $tdNow at the end of main paramenter. $tdNow is secondary.
//THINK: Set now parametes as in CA.
	public function getAnyAgendaForMonth( $month, $year, $appTypeId, $agId, $catId, $today = '', $now = '', $isTest = false, $orgCode = NULL ){
//	public static function getAnyAgendaForMonth( $dtNow, $mon, $year, $appTypeId, $agIdsStr, $isTest = false )
//$month, $year, $appTypeId, $agId, $catId, $today='', $now='', $orgCode=''
//	public static function getAnyAgendaForMonth( $dtNow, $mon, $year, $appTypeId, $agIdsStr, $isTest = false )

//		$info	= dbl::getAnyAgendaForMonth( $dtNow, $mon, $year, $appTypeId, $agIdsStr, $isTest );

		$org_code	= ( NULL == $orgCode) ? $_SESSION[ 'org_code' ] : $orgCode;

		$db_today	= ( '' != $today )	? date( 'Y-m-d', strtotime( $today ) ) : date( 'Y-m-d' );
		$db_now		= ( '' != $now )	? date( 'H:i:s', strtotime( $now ) ): date( 'H:i:s' );
		$d_t_now	= $db_today.' '.$db_now;

		$info	= dbl::getAnyAgendaForMonth( $month, $year, $appTypeId, $agId, $catId, $d_t_now, $isTest, $org_code );
		$info	= $info[ 0 ][ 'res' ];
		$info	= json_decode( $info, true );
		ksort( $info );
		return $info;
	}
//--------------------------------------------------------------------------------------------------

}// Class end
