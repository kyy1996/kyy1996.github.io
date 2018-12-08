<?php
/*
http认证
判断来路页面
写日志
*/
////////////禁用错误报告////////////// 
error_reporting(0);  
///////////http认证////////////

if(!isset($_SERVER['PHP_AUTH_USER']))
{
	header("Content-Type: text/html;charset=utf-8");
	header('HTTP/1.0 401 Unauthorized');
	header('WWW-Authenticate: Basic realm="Alen Blog Admin Panel"');
	echo 'Authentication&nbsp;is&nbsp;required.';
	exit;
}

$user = [
	'username' => 'alen',
	'password' => '9q8w7e1108'
];

if($_SERVER['PHP_AUTH_USER'] !== $user['username'] || $_SERVER['PHP_AUTH_PW'] !== $user['password']) {
	header('WWW-Authenticate: Basic realm="login:"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'login failed!';
        exit;	
}
?>
<!DOCTYPE html>
<html lang="zh-hans">
<head>
	<meta charset="UTF-8">
	<title>主动推送</title>
</head>
<body>
	<?php
	$urls = [];
	$XML = "../sitemap.xml";
	$XML = simplexml_load_file($XML);
	foreach ($XML->children() as $key => $value) {
		$urls[] = (string)$value->loc;
	};
	$urls[] = "http://www.kyy1996.com/";
	echo("待提交的URL：<br>\n");
	echo implode("<br>\n", $urls);
	echo("<br>");

	$api = 'http://data.zz.baidu.com/urls?site=www.kyy1996.com&token=hTFKd6Oh8o7u18ch';
	$ch = curl_init();
	$options =  array(
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => implode("\n", $urls),
		CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
	);
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	$result = json_decode($result);
	if($result->error) echo("<font color='red'>提交失败，".$result->message."</font><br>");
	else echo("提交成功。<br>
		成功提交的URL数量：".$result->success."<br>
		当天剩余的可推送url条数：".$result->remain."<br>
		由于不是本站url而未处理的url列表：".implode("<br>\n", $not_same_site)."<br>
		不合法的url列表：".implode("<br>\n", $not_valid)."<br>
		");
	?>
</body>
</html>
