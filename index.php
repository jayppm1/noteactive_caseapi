<?php 
// Version
define('VERSION', '1.5.6.4');

// Configuration
if (file_exists('config.php')) {
	require_once('config.php');
}  

// Install 
if (!defined('DIR_APPLICATION')) {
	echo "APPLICATION NOT INSTALL";
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');


// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);


$registry->set('noteactive', new Noteactive($registry));

// Encryption
$registry->set('encryption', new Encryption($config->get('config_encryption')));

/*
$configconn = parse_ini_file(DIR_CONFIG.'config.ini');
$servername = $configconn['servername'];
$decryptedservername = $registry->get('encryption')->decryptIt( $servername ) ;
//echo($decryptedservername);
$username = $configconn['username'];
$decryptedusername = $registry->get('encryption')->decryptIt( $username ) ;
//echo($decryptedusername);
$password = $configconn['password'];
$decryptedpassword = $registry->get('encryption')->decryptIt( $password ) ;
//echo($decryptedpassword);
$dbname = $configconn['dbname'];
$decrypteddbname = $registry->get('encryption')->decryptIt( $dbname ) ;
//echo($decrypteddbname);

*/

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

//$db = new DB(DB_DRIVER, $decryptedservername, $decryptedusername, $decryptedpassword, $decrypteddbname);
//$registry->set('db', $db);

/*
$servername = $configconn['warehouse_servername'];
$decryptedservername = $registry->get('encryption')->decryptIt( $servername ) ;
//echo($decryptedservername);
$username = $configconn['warehouse_username'];
$decryptedusername = $registry->get('encryption')->decryptIt( $username ) ;
//echo($decryptedusername);
$password = $configconn['warehouse_password'];
$decryptedpassword = $registry->get('encryption')->decryptIt( $password ) ;
//echo($decryptedpassword);
$dbname = $configconn['warehouse_dbname'];
$decrypteddbname = $registry->get('encryption')->decryptIt( $dbname ) ;
//$newdb = new DB(NEWDB_DRIVER, $decryptedservername, $decryptedusername, $decryptedpassword, $decrypteddbname);
//$registry->set('newdb', $newdb );

*/

$newdb = new DB(NEWDB_DRIVER, NEWDB_HOSTNAME, NEWDB_USERNAME, NEWDB_PASSWORD, NEWDB_DATABASE);
$registry->set('newdb', $newdb );

//$newdb = new DB(ADB_DRIVER, ADB_HOSTNAME, ADB_USERNAME, ADB_PASSWORD, ADB_DATABASE);
//$registry->set('adb', $newdb );


//$odbcdb = new DB(DB_ODBCDRIVER, DB_ODBCHOSTNAME, DB_ODBCUSERNAME, DB_ODBCPASSWORD, DB_ODBCDATABASE);
//$registry->set('odbcdb', $odbcdb);


// Store
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
	$store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {
	$store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}

if ($store_query->num_rows) {
	$config->set('config_store_id', $store_query->row['store_id']);
} else {
	$config->set('config_store_id', 0);
}
		
// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

if (!$store_query->num_rows) {
	$config->set('config_url', HTTP_SERVER);
	$config->set('config_ssl', HTTPS_SERVER);	
}

// Url
$url = new Url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));	
$registry->set('url', $url);

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}
		
	//if ($config->get('config_error_display')) {
		//echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b><br>';
	//}
	
	/*if ($config->get('config_error_log')) {
		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}*/

	return true;
}

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response); 

// Cache
$cache = new Cache('file');
$registry->set('cache', $cache); 
/*
// Session
$session = new Session();
$registry->set('session', $session);
*/

$session = new Session('db', $registry);
$registry->set('session', $session);


if (isset($_COOKIE[SESSION_NAME_1])) {
	$session_id = $_COOKIE[SESSION_NAME_1];
} else {
	$session_id = '';
}

$session->start($session_id);

setcookie(SESSION_NAME_1, $session->getId(), ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));



// Language Detection
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
	
	
}

$code = 'en';

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {	  
	setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}


$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Language	
$language = new Language($languages[$code]['directory']);


$language->load($languages[$code]['filename']);	
$registry->set('language', $language); 

// Document
$registry->set('document', new Document()); 		

// Customer
$registry->set('customer', new Customer($registry));

$registry->set('formkey', new formKey($registry));


$registry->set('awsimageconfig', new Awsimageconfig($registry));
//$registry->set('sftpconnection', new Sftpconnection($registry));
$registry->set('smsapi', new Smsapi($registry));

if (isset($request->get['tracking'])) {
	setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');
}
		


		
// Front Controller 
$controller = new Front($registry);


// SEO URL's
$controller->addPreAction(new Action('common/seo_url'));	
	
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home/login');
}
// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>