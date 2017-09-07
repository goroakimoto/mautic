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

	$mtc_id = $_GET['id'];
	echo "localStorage.setItem('mtc_id', '{$mtc_id}');\n";
}

echo $mtc_template;