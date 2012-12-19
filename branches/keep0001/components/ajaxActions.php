<?php
class ajaxActions{
	function __construct(){
		global $gl_Xajax;
		$gl_Xajax->registerFunction( array( "corePpskHandler", "PpskActions", "corePpskHandler" ) );
		$gl_Xajax->processRequests();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
?>