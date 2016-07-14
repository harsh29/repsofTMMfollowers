<?php

function requireDir($dir)
{
	if (!($ret = file_exists($dir))) {
		mkdir($dir);
	}
	return $ret;
}

define("VERSION", "Dev 110115#Halloween of Anonymous");

if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
  define("IS_WINDOWS_LOCAL", true);
else define("IS_WINDOWS_LOCAL", false);

if (!IS_WINDOWS_LOCAL) {
	requireDir("/var/www/wiki");
	if (!requireDir("/var/www/files") || !file_exists("/var/www/files/a")) {
		import("core/file/init.php");
		init_base("/var/www/files");
	}
}

define("DEBUG_MODE", true);

define("ROOT", $_SERVER['DOCUMENT_ROOT']);
// define("WIKI_DIR", "/var/www/wiki");
if (IS_WINDOWS_LOCAL)
  define("WIKI_DIR", "C:\\Users\\Lancelot\\Desktop\\wiki");
else
  define("WIKI_DIR", "/var/www/wiki");

// define("MYSQL_DATABASE_ADDRESS", "localhost");
if (IS_WINDOWS_LOCAL)
  define("MYSQL_DATABASE_ADDRESS", "127.0.0.1");
else define("MYSQL_DATABASE_ADDRESS", "127.0.0.1");
define("MYSQL_DATABASE_NAME", "inleak");
define("MYSQL_USER", "root");
if (IS_WINDOWS_LOCAL)
	define("MYSQL_PASSWD", "");
else define("MYSQL_PASSWD", "0d0e530008f4ed5a");
define("MYSQL_USERS_TABLE", "users");
define("MYSQL_CONFIG_TABLE", "config");

define("USER_LOGIN_TLL", 6000); // in seconds
define("DEFAULT_INITIAL_GROUP", 5);

// define("FILE_DIR", "/var/www/files");
if (IS_WINDOWS_LOCAL)
  define("FILE_DIR", "C:\\Users\\Lancelot\\Desktop\\sonia\\fb_local");
else define("FILE_DIR", "/var/www/files");
// define("GET_FILE_DIR", "fb");
if (IS_WINDOWS_LOCAL)
  define("GET_FILE_DIR", "fb_local");
else define("GET_FILE_DIR", "fb");

define("DEFAULT_ICON", "/img/default.jpg");
define("DEFAULT_COVER", "/img/a.jpg");

define("DISPLAY_VIEWPORT_INITIAL_SCALE", "1.0");

if (IS_WINDOWS_LOCAL)
  define("ACCESS_LOG", "C:\\Users\\Lancelot\\Desktop\\access_log");
else
  define("ACCESS_LOG", "/var/www/access_log");

define("BULLETIN_TIME_OUT", 30); // in seconds
define("INBOX_DIVIDING_RANGE", 1800); // in seconds
define("MESSAGE_SYSTEM_SENDER", "System");

/* Min Privileges
 * Group ID:
 * 0 => administrator
 * 1 => editor
 * 2 => norm
 *
 * Users of group id less or equal than the value can have the privilege
 */
define("PV_DASH_BOARD", 0);

define("PV_CHANGE_NICK_NAME", 2);
define("PV_CHANGE_SELF_INTRO", 2);
define("PV_CHANGE_GROUP_ID", 0);
define("PV_CHANGE_PASSWORD", 2);
define("PV_EDIT_OWN_WRITTEN_ENTRY", 1);
define("PV_EDIT_ALL_ENTRY", 0); /* some basic information including id, author, etc. */

?>
