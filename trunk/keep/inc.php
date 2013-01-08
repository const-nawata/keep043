<?php
require_once( 'config.php' );

global $gl_PpskPath; $gl_PpskPath = $gl_Path.'libs/ppsk/';
global $gl_PpskLogFile; $gl_PpskLogFile = $gl_Path.'keep.log';

DEFINE( '_XAJAX_JS_DIR', $gl_Path.'libs/xajax-0.5' );

require_once( $gl_Path.'prompts/russian.php' );
require_once( $gl_PpskPath.'inc_ppsk.php' );
require_once( $gl_Path.'css/css_constants.php' );
require_once( $gl_Path.'libs/utils/constants.php' );
require_once( $gl_Path.'prompts/tab_prompts.php' );
require_once( $gl_Path.'libs/utils/DbConnect.php' );
require_once( $gl_Path.'libs/utils/funcs.php' );
require_once( $gl_Path.'libs/utils/KeepDbl.php' );
require_once( $gl_Path.'RunApp.php' );

global $gl_CompsPath; $gl_CompsPath	= $gl_Path.'components/';
require_once( $gl_CompsPath.'inc_comps.php' );

require_once( $gl_Path.'libs/xajax-0.5/xajax_core/xajax.inc.php' );
