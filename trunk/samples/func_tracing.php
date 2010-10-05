<?php
function af(){
	bf();
}

function bf(){
	cf();
}

function cf(){
	$trace = debug_backtrace();
	echo '<pre>'.print_r( $trace, true ).'</pre>';
}
//af();
?>