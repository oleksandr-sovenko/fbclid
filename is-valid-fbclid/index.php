<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$db = new SQLite3(__DIR__.'/ids.sqlite3');
$db->exec('
	CREATE TABLE IF NOT EXISTS ids (
    	id INTEGER PRIMARY KEY,
    	created DATETIME,
    	fbclid TEXT,
    	user_agent TEXT,
    	ipaddr TEXT
	)
');

/**
 * 
 */
function add_id($fbclid = '', $user_agent = '', $ipaddr = '') {
	global $db;

   	$stmt = $db->prepare('INSERT INTO ids (created, fbclid, user_agent, ipaddr) VALUES (:created, :fbclid, :user_agent, :ipaddr)');
   	$stmt->bindParam(':created', date('Y-m-d H:i:s'), SQLITE3_DATETIME);
   	$stmt->bindParam(':fbclid', $fbclid, SQLITE3_TEXT);
   	$stmt->bindParam(':user_agent', $user_agent, SQLITE3_TEXT);
   	$stmt->bindParam(':ipaddr', $ipaddr, SQLITE3_TEXT);
   	$stmt->execute();
}

/**
 *
 */
function if_exists_id($fbclid) {
	global $db;

    $stmt = $db->prepare('SELECT COUNT(*) FROM ids WHERE fbclid = :fbclid');
    $stmt->bindValue(':fbclid', $fbclid, SQLITE3_TEXT);
    $result = $stmt->execute();
    $count = $result->fetchArray(SQLITE3_NUM)[0];

    if ($count > 0)
        return true;
    else
        return false;
}

$fbclid = !empty($_GET['fbclid']) ? $_GET['fbclid'] : '';
$result = false;
$exists = false;

if ($fbclid) {
	$exists = if_exists_id($fbclid);

	if (!$exists)
		add_id($fbclid, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']);

	if (strpos($fbclid, 'IwAR') !== false and
		preg_match('/[a-zA-Z]/', $fbclid) and
		preg_match('/[0-9]/', $fbclid) and
		strlen($fbclid) > 20
	) {
		$result = true;
	}
}

$db->close();

if ($exists)
	$result = false;

die(json_encode(['fbclid' => $fbclid, 'result' => $result]));

?>