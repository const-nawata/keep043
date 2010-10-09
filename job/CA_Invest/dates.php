<?php
$mk			= ( !isset( $d_t_now ) || NULL	== $d_t_now ) ? mktime() : strtotime( $d_t_now );

$bef_yest		= date( 'd-m-Y', strtotime( '-2 day', $mk ) );
$db_bef_yest	= date( 'Y-m-d', strtotime( '-2 day', $mk ) );

$yest		= date( 'd-m-Y', strtotime( '-1 day', $mk ) );
$db_yest	= date( 'Y-m-d', strtotime( '-1 day', $mk ) );

$today		= date( 'd-m-Y', $mk );
$db_today	= date( 'Y-m-d', $mk );

$tomor		= date( 'd-m-Y', strtotime( '+1 day', $mk ) );
$db_tomor	= date( 'Y-m-d', strtotime( '+1 day', $mk ) );

$aft_tomor		= date( 'd-m-Y', strtotime( '+2 day', $mk ) );
$db_aft_tomor	= date( 'Y-m-d', strtotime( '+2 day', $mk ) );

$db_date	= date( 'Y-m-d', strtotime( '+10 day', $mk ) );
$date		= date( 'd-m-Y', strtotime( '+10 day', $mk ) );

$time_now	= date( 'H:i', $mk );
?>