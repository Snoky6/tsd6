<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/* custom variables - Aan te passen per gebruiker (zou ook in DB kunnen maar das een extra transactie) */
define('global_bedrijfsnaam', 'Chelsey');
//define('global_websiteURL', 'https://www.chelseyfashionbeauty.com/');
define('global_websiteURL', 'http://localhost:88/Chelsey/');

define('global_webshopemail', 'jeroen_vinken@hotmail.com');
define('global_adminlogin', 'Chelsey');
define('global_adminloginww', 'Chelsey123');
define('global_facebooklink', 'https://www.facebook.com/chelseysmetspage?fref=ts');
define('global_mollieAPIKeyLive', 'test_h53xmpjDsfGVAxfPNauJERNaWeR8H6');
define('global_bpostid', '123456789');
define('global_bpostww', 'xxx');


/* End of file constants.php */
/* Location: ./application/config/constants.php */