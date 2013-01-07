<?php
class ajaxActions{
	function __construct(){
		global $gl_Xajax;
// 		$gl_Xajax->registerFunction( array( "corePpskHandler", "PpskActions", "corePpskHandler" ) );
// 		$gl_Xajax->processRequests();

		$gl_Xajax->register(XAJAX_FUNCTION, array("corePpskHandler", "PpskActions", "corePpskHandler"));
		$gl_Xajax->processRequest();
	}
	//--------------------------------------------------------------------------------------------------

}//	Class end
