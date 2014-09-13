<?php
function create_htaccess()
{
    $content = "order deny,allow\ndeny from all";
    if (!$file = fopen('database/.htaccess', 'w')) {
        echo 'Cannot create .htaccess-file';
        exit;
    }
    if (!fwrite($file, $content)) {
        echo 'Cannot write into .htaccess-file';
        exit;
    }

    fclose($file);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$filename = $_SERVER['SCRIPT_NAME'];

/* create SQLITE3-database */
class MyDB extends SQLite3
{
    function __construct()
    {
        if (!file_exists('database/database.sqlite3')) {
            mkdir('database', 0700) or die('Cannot create folder "database"');
            create_htaccess();
            $this->open('database/database.sqlite3', SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
            $this->exec('CREATE TABLE data (access_key char(32), content varchar(1048576))');
            //the database shouldn't be accessed by everyone
            chmod("database/database.sqlite3", 0600) or die('Cannot set access rights for database.sqlite3 (chmod 0600)');
        } else {
            //if it is the case, that you move the database file manually to the folder
            chmod('database', 0700) or die('Cannot set access rights for "database"-folder (chmod 0700)');
            chmod("database/database.sqlite3", 0600) or die('Cannot set access rights for "database.sqlite3"-file (chmod 0600)');
            $this->open('database/database.sqlite3', SQLITE3_OPEN_READWRITE);
        }
    }
}

$db = new MyDB();

if (isset($_POST['text'])) {
    //insert content to database
    $bytes = openssl_random_pseudo_bytes(16, $cstrong);
    $access_key = bin2hex($bytes);
    $content = $db->escapestring($_POST['text']);
    $db->exec("INSERT INTO data (access_key, content) VALUES ('$access_key', '$content')");
    $body = "<a href=\"http://$_SERVER[SERVER_NAME]/get.php?akey=$access_key\">view</a>";
} else if (isset($_GET['akey'])) {
    //access note if possible
    $result = $db->query('SELECT content FROM data WHERE access_key =\'' . $db->escapestring($_GET['akey']) . '\'');
    $body = $result->fetchArray(SQLITE3_ASSOC);
    $body = '<pre>' . htmlspecialchars($body['content']) . '</pre>';

} else {
    $body = "<form action=\"$filename\"" . ' method="post">
    </form>';
}

echo $body;