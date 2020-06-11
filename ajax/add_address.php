<?php



/**
 *	language
 */
mb_language('Japanese');
mb_internal_encoding('UTF-8');



/**
 *	include
 */
require_once( '../config/define.inc' );
require_once( '../config/functions.inc' );



/**
 *	init
 */
$db = db();

$db->query(sprintf(
		'update marker set address = "%s" where id = "%s"',
		$_POST['address'],
		$_POST['id']
	));
echo 'OK';