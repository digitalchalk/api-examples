<?php
	
	require_once('apicommon.php');
	require_once('apiconfig.php');
	
	$dataToSend = array(
		'email' => 'bobrob@sprucepine.com',
		'id' => 'foo'
	);
	
	$encoded = http_build_query($dataToSend);

	echo $encoded;
?>