<?php
global $CA_PATH;

$agendas	= &$_SESSION[ 'agendas' ];
$cats		= &$_SESSION[ 'cats' ];
$clients	= &$_SESSION[ 'clients' ];
$app_types	= &$_SESSION[ 'app_types' ];
$apps		= &$_SESSION[ 'apps' ];
$free_times	= &$_SESSION[ 'free_times' ];
$off_days	= &$_SESSION[ 'off_days' ];

include( $CA_PATH.'dates.php' );
?>