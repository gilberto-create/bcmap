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
$stmt = $db->query(sprintf(
			'update marker set deleted = 1 where lat = "%s" and lng = "%s" and user = "%s"',
			$_POST['lat'],
			$_POST['lng'],
			$_POST['user']
		));
echo $stmt->rowCount();