<?php

header('Content-Type: text/javascript');

$HTTP_HOST = $_SERVER['HTTP_HOST'];
$mtc_js_url = "https://{$HTTP_HOST}/mtc.js";

$options = [
	"ssl" => [
		"verify_peer" => false,
		"verify_peer_name" => false
	]
];

$mtc_template = file_get_contents($mtc_js_url, false, stream_context_create($options));

if (isset($_GET['id']) && $_GET['id'] != '' && intval($_GET['id']) > 0) {
	
	$person_id = intval($_GET['id']);
	// mautic_id に変換する必要がある
	
	$link = mysqli_connect('127.0.0.1', 'mauticuser', 'mauticpassword', 'mauticdb');
	$sql = "select id from leads where person_id={$person_id} limit 1";
	
	if ($result = $link->query($sql)) {
		
		// 連想配列を取得
		$row = $result->fetch_assoc();
		if (!empty($row) && isset($row['id'])) {

			$mtc_id = $row['id'];
			@session_destroy();
			if (isset($_SERVER['HTTP_COOKIE'])) {
				$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
				foreach($cookies as $cookie) {
					$parts = explode('=', $cookie);
					$name = trim($parts[0]);
					setcookie($name, '', time()-1000);
					setcookie($name, '', time()-1000, '/');
				}
			}
			
			echo "localStorage.setItem('mtc_id', '{$mtc_id}');\n";
			
		}
		$result->close();
	}
	
	mysqli_close($link);
	
}

echo $mtc_template;