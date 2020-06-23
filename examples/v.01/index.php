<?php
require 'config/config.php';
class DB {
    public static function connect()
    {
        $params = Config::db();
        $conn = new mysqli($params['host'], $params['user'], $params['password']);
        $conn->query("CREATE DATABASE IF NOT EXISTS {$params['dbname']}");
        $conn->close();
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']};charset={$params['charset']};";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            return new PDO($dsn, $params['user'], $params['password'], $opt);
        } catch (PDOException $e) {
            echo 'Ошибка соединения с базой данных ' , $e->getMessage();
            die;
        }

    }
    public static function create($pdo){
        $f = fopen('database/query.txt', 'r');
        $bool = fgets($f, 6);
        fclose($f);
        if ($bool === "true") {
            $pdo->query("CREATE TABLE IF NOT EXISTS `images` (`id` int(12) NOT NULL,`src` varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Images';");
            $pdo->query("INSERT IGNORE INTO `images` (`id`, `src`) VALUES
        (1, 'https://sun9-68.userapi.com/c858528/v858528614/112707/58hXEbFTGTI.jpg'),
        (2, 'https://stihi.ru/pics/2017/01/05/8224.jpg'),
        (3, 'https://golos.ua/images/items/2018-10/23/uxSt5iPO7HQE22nx/img_top.jpg'),
        (4, 'https://i.pinimg.com/736x/96/5f/83/965f83a29f6051fe4e97c6b209d06f96.jpg'),
        (5, 'https://i.pinimg.com/736x/23/f6/b1/23f6b1958d9edef45d127f5d33531647.jpg'),
        (6, 'https://backiee.com/static/wpdb/wallpapers/1000x563/175107.jpg'),
        (7, 'https://i.pinimg.com/736x/31/8e/a6/318ea62c675e94046d854838bab4f619.jpg'),
        (8, 'https://99px.ru/sstorage/56/2013/09/image_561909130053058247941.jpg'),
        (9, 'https://live.staticflickr.com/7333/9659227740_4ca10e7126_b.jpg'),
        (10, 'https://collegevilleinstitute.org/wp-content/uploads/2016/08/S3XBRRS2D9-e1470277138792.jpg'),
        (11, 'https://sun9-68.userapi.com/c858528/v858528614/112707/58hXEbFTGTI.jpg'),
        (12, 'https://stihi.ru/pics/2017/01/05/8224.jpg'),
        (13, 'https://golos.ua/images/items/2018-10/23/uxSt5iPO7HQE22nx/img_top.jpg'),
        (14, 'https://i.pinimg.com/736x/96/5f/83/965f83a29f6051fe4e97c6b209d06f96.jpg'),
        (15, 'https://i.pinimg.com/736x/23/f6/b1/23f6b1958d9edef45d127f5d33531647.jpg'),
        (16, 'https://backiee.com/static/wpdb/wallpapers/1000x563/175107.jpg'),
        (17, 'https://i.pinimg.com/736x/31/8e/a6/318ea62c675e94046d854838bab4f619.jpg'),
        (18, 'https://99px.ru/sstorage/56/2013/09/image_561909130053058247941.jpg'),
        (19, 'https://live.staticflickr.com/7333/9659227740_4ca10e7126_b.jpg'),
        (20, 'https://collegevilleinstitute.org/wp-content/uploads/2016/08/S3XBRRS2D9-e1470277138792.jpg');
        ");
            $f = fopen('query.txt', 'w+');
            fwrite($f, 'false');
            fclose($f);
        }
    }
}

$pdo = DB::connect();
DB::create($pdo);
$_POST['0'] = '';
/**
 * Class PaginationPDO for PDO
 */
class PaginationPDO{

    public static string $table = 'images'; // Name of table
    public static int $pageLimit = 5;       //Max elements om page

    public static function getElements(PDO $pdo, array $fields, int $offset) : array
    {
          return $pdo->query("SELECT ".implode(',', $fields)." FROM ".self::$table." ORDER BY `id` ASC LIMIT ".self::$pageLimit." OFFSET ". ($offset * self::$pageLimit))->fetchAll();
    }
    public static function countRows(PDO $pdo) : int
    {
        return $pdo->query("SELECT `id` FROM ".self::$table." ORDER BY `id` DESC LIMIT 1")->fetch()['id'] / self::$pageLimit;
    }
}
$amount = PaginationPDO::countRows($pdo);


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Easy-pagination v0.1</title>
</head>
<link rel="stylesheet" href="css/style.css">
<body>
<section>
    <div class='container'>
<?php

    /**
     * id and src - fields to execute
     **/
$elements = PaginationPDO::getElements($pdo, ['id', 'src'], array_key_first($_POST));
foreach ($elements as $element){
    echo "<div class='item'>", "<img src=". $element['src'] . ">",'</div>';
}

?>
    </div>
<form action="http://easy-pagination/examples/v.01/" method="post">
    <button type="submit" name="0">Start</button>
<?php
       for($i=0;$i<$amount;$i++){
            echo "<button type='submit' name='$i'>$i</button>";
        }
    ?>
    <button type="submit" name="<?=$amount - 1;?>">End</button>
</form>
</section>
</body>
</html>
