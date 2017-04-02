<?php
// create curl resource
$ch = curl_init();
// set url
//curl_setopt($ch, CURLOPT_URL, "http://www.i.ua");
//CURLOPT_HEADER CURLINFO_RESPONSE_CODE
//return the transfer as a string
// установка URL и других необходимых параметров
curl_setopt($ch, CURLOPT_URL, "http://www.i.ua/");
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_NOBODY, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_getinfo();
// $output contains the output string
$output = curl_exec($ch);
//var_dump($output);
$code = curl_getinfo($ch,CURLINFO_RESPONSE_CODE);
curl_close($ch);
var_dump($code);
// close curl resource to free up system resources 
//
//die();

// $opts = array(
//   'http'=>array(
//     'method'=>"HEAD",
//     'header'=>"Accept-language: en\r\n" .
//               "Cookie: foo=bar\r\n"
//   )
// );

// $context = stream_context_create($opts);
// $file  = file_get_contents("http://localhost", false, $context);
// echo $file; die();
require_once '../vendor/autoload.php';

use \Symfony\Component\HttpKernel\Client as Client;

$client = new Client();

$crawler = $client->request("GET", "https://rss.unian.net/site/news_ukr.rss"); 
var_dump($crawler);
die();
$app = new Silex\Application;
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    // 'dbname' => 'rss_news',
    'user' => 'root',
    'password' => '123',
    'charset'   => 'utf8mb4',
    )
));

$sql = "SHOW DATABASES LIKE 'rss_news';";
$statement = $app['db']->executeQuery($sql);
if (!$user = $statement->fetch()) {
	echo "CREATE DATABASE rss_news";
    $sql = "CREATE DATABASE rss_news CHARACTER SET utf8 COLLATE utf8_general_ci;";
	$statement = $app['db']->executeQuery($sql);
}
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db1.options' => array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname' => 'rss_news',
    'user' => 'root',
    'password' => '123',
    'charset'   => 'utf8mb4',
    )
));

// use Pimple\Container;

// $container = new Container();

// $container['cookie_name'] = 'SESSION_ID';
// $container['session_storage_class'] = 'SessionStorage';

// $container['session_storage'] = function ($c) {
	// 	return new $c['session_storage_class']($c['cookie_name']);
	//
// }
// ;
// //var_dump($container['session_storage']($container));
// ($container['session_storage'])($container);
// $container = new Container();
// var_dump($container);
// // define some services
// $container['session_storage'] = function ($c) {
	// 	return new SessionStorage('SESSION_ID');
	//
// }
// ;
// var_dump($container['session_storage']);
// $container['session'] = function ($c) {
	// 	return new Session($c['session_storage']);
	//
// }
// ;
// $session = $container['session'];
// var_dump($session);
