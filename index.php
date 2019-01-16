<?php
use Core\Classes\Router;

define('ROOT', dirname(__FILE__));

function replaceUnderscores ( $classname ) {
    $path = str_replace( '_', DIRECTORY_SEPARATOR, $classname);
    if (file_exists( "{$path}.php")){
        require_once("{$path}.php");
    }
}

function namespaceAutoLoad($path){
    if(preg_match("/\\\\/", $path)){
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
    }
    if(file_exists("{$path}.php")){
        require_once("{$path}.php");
    }
}

spl_autoload_register( 'replaceUnderscores') ;

spl_autoload_register('namespaceAutoLoad');

Router::getInstance()->execute();

$dbconn = pg_connect("host=localhost port=5432 dbname=test user=test password=test")
or die('Could not connect: ' . pg_last_error());

$query = 'SELECT * FROM posts';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

pg_close($dbconn);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<ul>
    <?php while ($row = pg_fetch_object($result)): ?>
    <li>
        Title: <?php echo $row->title ?><br>
        Description: <?php echo $row->description ?><br>
        Date: <?php echo $row->date ?><br>
        <hr>
    </li>
    <?php endwhile; ?>
</ul>
</body>
</html>