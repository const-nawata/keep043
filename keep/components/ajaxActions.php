<?php
class ajaxActions{

	function __construct(){
		global $gl_Xajax;

		$gl_Xajax->register( XAJAX_FUNCTION, array( 'onHandler', 'PpskActions', 'onHandler' ));
		$gl_Xajax->processRequest();
	}
//______________________________________________________________________________

}//	Class end
