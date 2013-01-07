<?php
/**
 * does information logging.
 * Set $gl_PpskLogFile global variable.
 * $gl_PpskLogFile default value is set in PPSK root directory and has `ppsk_inro.log` file name.
 *
 */
class Log{
	private $fhandle;

	public function __construct(){
		global $gl_PpskLogFile, $gl_PpskPath;
		$gl_PpskLogFile	= ( !(bool)$gl_PpskLogFile ) ? $gl_PpskPath.'ppsk_info.log' : $gl_PpskLogFile;
		$this->fhandle = fopen( $gl_PpskLogFile, 'a' );
	}
//______________________________________________________________________________

	private static function getDateTime(){
		$mkarr	= gettimeofday() ;
		$mktime	= (int)$mkarr['usec'];


		if( $mktime < 10 ){
			$mktime	= '00000'.$mktime;
		}elseif(  $mktime < 100 ){
			$mktime	= '0000'.$mktime;
		}elseif(  $mktime < 1000 ){
			$mktime	= '000'.$mktime;
		}elseif(  $mktime < 10000 ){
			$mktime	= '00'.$mktime;
		}elseif(  $mktime < 100000 ){
			$mktime	= '0'.$mktime;
		}
		return date('d-m-Y H:i:s', (int)$mkarr['sec'] ).'.'.$mktime.' *** PPSK Logger *** ';
	}
//______________________________________________________________________________

	public static function putLogInfo( $message, $type='info' ){
		$log_obj	= new Log();

		$message	=  "\n\nMessage type: ".$type.' *** '.self::getDateTime().$_SERVER['DOCUMENT_ROOT'].
			"\n------------------------------------------------------------------------\n".$message;

		$fhandle = $log_obj->fhandle;
		fwrite( $fhandle, $message );
		fclose( $fhandle);
	}
//______________________________________________________________________________

}//	Class end
