<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'mesaverde.terradex.com';
$db['default']['username'] = 'terradex_user';
$db['default']['password'] = 'g30th1ngs';
$db['default']['database'] = 'terradex';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$db['csms']['hostname'] = 'terradex.com';
$db['csms']['username'] = 'csms';
$db['csms']['password'] = 'csms$855';
$db['csms']['database'] = 'csms';
$db['csms']['dbdriver'] = 'mssql';
$db['csms']['dbprefix'] = '';
$db['csms']['pconnect'] = FALSE;
$db['csms']['db_debug'] = TRUE;
$db['csms']['cache_on'] = FALSE;
$db['csms']['cachedir'] = '';
$db['csms']['char_set'] = 'utf8';
$db['csms']['dbcollat'] = 'utf8_general_ci';

$db['msterradex']['hostname'] = 'terradex.com';
$db['msterradex']['username'] = 'terradex';
$db['msterradex']['password'] = 'terradex$855';
$db['msterradex']['database'] = 'terradex';
$db['msterradex']['dbdriver'] = 'mssql';
$db['msterradex']['dbprefix'] = '';
$db['msterradex']['pconnect'] = FALSE;
$db['msterradex']['db_debug'] = TRUE;
$db['msterradex']['cache_on'] = FALSE;
$db['msterradex']['cachedir'] = '';
$db['msterradex']['char_set'] = 'utf8';
$db['msterradex']['dbcollat'] = 'utf8_general_ci';

$db['pgterradex']['hostname'] = 'mesaverde.terradex.com';
$db['pgterradex']['username'] = 'postgres';
$db['pgterradex']['password'] = '';
$db['pgterradex']['database'] = 'terradex';
$db['pgterradex']['dbdriver'] = 'postgre';
$db['pgterradex']['dbprefix'] = '';
$db['pgterradex']['pconnect'] = FALSE;
$db['pgterradex']['db_debug'] = TRUE;
$db['pgterradex']['cache_on'] = FALSE;
$db['pgterradex']['cachedir'] = '';
$db['pgterradex']['char_set'] = 'utf8';
$db['pgterradex']['dbcollat'] = 'utf8_general_ci';
$db['pgterradex']['swap_pre'] = '';
$db['pgterradex']['autoinit'] = TRUE;
$db['pgterradex']['stricton'] = FALSE;
$db['pgterradex']['port'] = 5432;

/* End of file database.php */
/* Location: ./application/config/database.php */