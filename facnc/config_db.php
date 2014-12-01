<?php

/*Connection Information for the database
$def_coy - the default company that is pre-selected on login

'host' - the computer ip address or name where the database is. The default is 'localhost' assuming that the web server is also the sql server.

'dbuser' - the user name under which the company database should be accessed.
  NB it is not secure to use root as the dbuser with no password - a user with appropriate privileges must be set up.

'dbpassword' - the password required for the dbuser to authorise the above database user.

'dbname' - the name of the database as defined in the RDMS being used. Typically RDMS allow many databases to be maintained under the same server.
'tbpref' - prefix on table names, or '' if not used. Always use non-empty prefixes if multiply company use the same database.
*/


$def_coy = 0;

$tb_pref_counter = 17;

$db_connections = array (
  0 => 
  array (
    'name' => 'mastercompany',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => 'mysql@local',
    'dbname' => 'connectncabs',
    'tbpref' => '0_',
  ),
  1 => 
  array (
    'name' => 'TALC1',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => 'mysql@local',
    'dbname' => 'connectncabs',
    'tbpref' => '1_',
  ),
  2 => 
  array (
    'name' => 'CONNECTNCABS',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => 'mysql@local',
    'dbname' => 'connectncabs',
    'tbpref' => '2_',
  ),
  4 => 
  array (
    'name' => 'testcoy',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => 'mysql@local',
    'dbname' => 'connectncabs',
    'tbpref' => '4_',
  ),
  3 => 
  array (
    'name' => 'acubeinnovations',
    'host' => 'localhost',
    'dbuser' => 'root',
    'dbpassword' => 'mysql@local',
    'dbname' => 'connectncabs',
    'tbpref' => '3_',
  ),
);
?>
