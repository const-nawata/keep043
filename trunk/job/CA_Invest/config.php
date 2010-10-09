<?php
session_start();
global $CA_PATH, $gl_MysqliObj;
global $Host, $DBName, $User, $Pass;
$CA_PATH	= "./";

//Configuration of Database
$Host	= "localhost";
$DBName	= 'ca';
$User	= "nawata";
$Pass	= "043";

//$gl_MysqliObj = new mysqli( $this->mHost, $this->mUser, $this->mPass, $this->mDbName );

//$my_sql_link = @mysql_connect( $Host, $User, $Pass );
//$is_db_select = @mysql_select_db( $DBName );
?>