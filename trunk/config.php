<?php
/**
 * Configuration file
 * Created by Constantine Nawata (nawata@mail.ru) on 28-06-2010.
 */

define ( '_EX', 'Caught exception: ' );
global $_SESSION;
global $gl_Path; $gl_Path = './';
global $gl_Xajax, $dbh, $log_obj;

$host		= "localhost";
$db_name	= 'keep';
$db_user	= 'root';
$user_pass	= '043';

global $ifxAjaxError;	//	Don't change name of this variable accoding to conventions. This name is defined by ajax requirements.
$ifxAjaxError	= 1;
?>