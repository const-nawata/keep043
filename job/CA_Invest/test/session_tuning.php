<?php
class session_tuning{

    public static function initSessionData(){
		$_SESSION[ 'agendas' ]		= array();
		$_SESSION[ 'cats' ]			= array();
        $_SESSION[ 'clients' ]		= array();
        $_SESSION[ 'app_types' ]	= array();
        $_SESSION[ 'apps' ]			= array();
        $_SESSION[ 'free_times' ]	= array();
        $_SESSION[ 'off_days' ]		= array();
    }
}
?>