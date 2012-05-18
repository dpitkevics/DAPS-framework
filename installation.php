<?php
if(isset($_POST['submit'])) {
    extract($_POST);
    
    $error = 0;
    if(empty($dbhost))
        $error = 1;
    if(empty($dbuser))
        $error = 1;
    if(empty($dbpass))
        $error = 1;
    if(empty($dbname))
        $error = 1;
    if(empty($serveraddr))
        $error = 1;
    if(empty($diraddr))
        $error = 1;
    
    if($error == 1) {
        die("Please fill in all fields");
    }
    
    
    // Creating ROOT .htaccess file
    $fp = fopen('.htaccess', 'w');
    $var = '
    <IfModule mod_rewrite.c>
        Options +FollowSymLinks

        RewriteEngine on
        RewriteCond %{HTTP_HOST} ^'. $serveraddr . $diraddr .'$
        RewriteRule (.*) '. $serveraddr . $diraddr .'/$1 [R=301,L]
        RewriteRule ^$ Public [L]

        Options All -Indexes
    </IfModule>    
    ';
    fwrite($fp, $var);
    fclose($fp);
    
    // Creating SYSTEM Config.php file
    $fp = fopen('System/Config.php', 'w');
$var = '<?php

/*
* Configuration Variables
*/

define (\'DEVELOPMENT_ENVIRONMENT\',true);

define(\'DB_HOST\', \''. $dbhost .'\');
define(\'DB_USER\', \''. $dbuser .'\');
define(\'DB_PASSWORD\', \''. $dbpass .'\');
define(\'DB_NAME\', \''. $dbname .'\');

define(\'BASE_PATH\', \''. $serveraddr . $diraddr .'\');
define(\'BASE_DIR\', \''. $diraddr .'/Public/\');
define(\'PAGINATE_LIMIT\', \'5\');    
';
    fwrite($fp, $var);
    fclose($fp);
    
    $res = mysql_connect($dbhost, $dbuser, $dbpass) or die("Cant connect to MySQL");
    $con = mysql_selectdb($dbname, $res) or die("Cant select database");
    $query = '
    CREATE TABLE basics (
    id int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(64) COLLATE utf8_latvian_ci NOT NULL,
    job varchar(64) COLLATE utf8_latvian_ci NOT NULL,
    about text COLLATE utf8_latvian_ci NOT NULL,
    PRIMARY KEY (id)
    ) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_latvian_ci;
    ';
    $query2 = '
    INSERT INTO basics (name, job, about) VALUES ("Daniels", "Web", "Hard work to create DAPS");  
    ';
    $query = mysql_query($query) or die('Could not execute test query');
    $query2 = mysql_query($query2) or die('Could not insert test data');

    die ("Installation is Successful! Now please delete installation.php and index.php file in ROOT directory");
}
?>
<html>
    <head>
        <title>DAPS Installation</title>
    </head>
    <body>
        <h1>
            DAPS Framework
        </h1>
        <h2>
            Installation
        </h2>
        <p><b>Enter needed information</b></p>
        <p>Database connection: </p>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
        <table>
            <tr>
                <td>Database host</td>
                <td><input type="text" name="dbhost" /></td>
            </tr>
            <tr>
                <td>Database user</td>
                <td><input type="text" name="dbuser" /></td>
            </tr>
            <tr>
                <td>Database password</td>
                <td><input type="password" name="dbpass" /></td>
            </tr>
            <tr>
                <td>Database name</td>
                <td><input type="text" name="dbname" /></td>
            </tr>
        </table>
        <p>Adresses: </p>
        <table>
            <tr>
                <td>Server address (without specified paths, example: http://localhost <span style="color: red;">NB! Without '/' at the end</span>)</td>
                <td><input type="text" name="serveraddr" /></td>
            </tr>
            <tr>
                <td>Subdirectories (example: /daps/ <span style="color: red;">NB! Should be '/' at the beginning and not at the end</span>)</td>
                <td><input type="text" name="diraddr" /></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Begin Installation" /></td>
            </tr>
        </table>
        </form>
    </body>
</html>