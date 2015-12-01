<?
	define('DB_HOST','localhost');
	define('DB_USER','root');
	define('DB_PASSWORD','');
	define('DB_NAME','zzz_1');
	define('DB_PREFIX','');

	DEFINE('DEBUG',TRUE);


	    define('WEBSITE',"/www.testforum.xxx");
	define('GLOBAL_URL',"http://217.73.84.223:18888".WEBSITE);

	DEFINE('GLOBAL_DIR',dirname(__FILE__));
	DEFINE('APP_DIR',GLOBAL_DIR.'/_app/');
	DEFINE('PATH_LOGS',GLOBAL_DIR.'/_logs/');
	DEFINE('PATH_CACHE',GLOBAL_DIR.'/../_cache/');
	DEFINE('CRON_CACHE_DIR',GLOBAL_DIR.'/_cron_cache/');

	DEFINE('TEMPLATES_DIR',GLOBAL_DIR."/templates/");

	DEFINE('IMAGES_URL',WEBSITE."/static/images/");
	DEFINE('CSS_URL',WEBSITE."/static/css/");
	DEFINE('JS_URL',WEBSITE."/static/js/");

    DEFINE('EMAIL_SUPPORT',"support@local.com");

	DEFINE("COOKIES_PREF", "tf_"); // Префекс к кукам

?>