<?php
function getDbArray(){
	global $mysqli_obj;

	$sql	= "CALL `get_json_array`()";
	$info	= array();
	if( $mysqli_obj->multi_query( $sql ) ){
		while( $result = $mysqli_obj->use_result() ){
			while( $row = $result->fetch_array( MYSQLI_ASSOC ) ){
				$info = $row[ 'clients' ];
			}
			$result->close();
			$next_result	= $mysqli_obj->next_result();
		}
	}


	$inf		= json_decode( $info );

	echo "Example:";
	echo '<pre>'.print_r( $info, true ).'</pre>';
	echo "<br>Decode by command: ".'$inf = json_decode( $info );';
	echo '<pre>'.print_r( $inf, true ).'</pre>';
	echo "-------------------------------------------------------------------------------------------------------------<br>";


	//	Test comment for Google SVN
	
	$sql	=
"SELECT `json`.`item` AS `item` FROM `json`";
	$result	= $mysqli_obj->query( $sql );
	if( $result ){
		while( $row = $result->fetch_assoc() ){
			echo "Example:";
			echo '<pre>'.print_r( $row[ 'item' ], true ).'</pre>';
			echo "<br>Decode by command: ".'$inf = json_decode( $row ); (Object)';
			$inf = json_decode( $row[ 'item' ] );
			echo '<pre>'.print_r( $inf, true ).'</pre>';

			echo "<br>Decode by command: ".'$inf = json_decode( $row, true ); (Array)';
			$inf = json_decode( $row[ 'item' ], TRUE );
			echo '<pre>'.print_r( $inf, true ).'</pre>';
		}
		echo "-------------------------------------------------------------------------------------------------------------<br>";
	}

	return $info;
}
?>