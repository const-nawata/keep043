<?php

/**
 * get_ext_js
 *
 * @return string
 */
function get_ext_js( $this_obj = NULL ){
	return $this_obj != NULL
		? ''
		.$this_obj->javascript->external('https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js')
		.$this_obj->javascript->external('//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js', TRUE )
		.$this_obj->javascript->external(base_url().'assets/js/main.js')
		: ''
	;
}
//------------------------------------------------------------------------------
