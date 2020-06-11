<?php



/**
 *	language
 */
mb_language('Japanese');
mb_internal_encoding('UTF-8');
session_start();

unset( $_SESSION['login_user'] );
header( 'location: ../' );