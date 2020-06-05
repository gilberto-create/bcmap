<?php



/**
 *	db
 */
function db() {
	
	static $db;
	
	if( !isset( $db ) ) {
		
		try {
			
			$db = new PDO(
					'mysql:'.
					'host='. DB_HOST. ';'.
					'dbname='. DB_NAME,
					DB_USER,
					DB_PASS
				);
			
		} catch( PDOException $e ) {
			
			echo $e->getMessage();
			exit;
		}
	}
	
	return $db;
}