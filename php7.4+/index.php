<?php
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

/**
 * Class PaginationMysqli for mysqli
 */
class PaginationMysqli{

    public static string $table = 'images'; // Name of table
    public static int $pageLimit = 5;       //Max elements om page

    public static function getElements(mysqli $mysqli, array $fields, int $offset) : array {
        return $mysqli->query("SELECT ".implode(',', $fields)." FROM ".self::$table." ORDER BY `id` ASC LIMIT ".self::$pageLimit." OFFSET ". ($offset * self::$pageLimit))->fetch_all();
    }
    public static function countRows(mysqli $mysqli) : int
    {
        return $mysqli->query("SELECT `id` FROM ".self::$table." ORDER BY `id` DESC LIMIT 1")->fetch_assoc()['id'] / self::$pageLimit;
    }
}
/**
 * $amount = PaginationPDO::countRows($pdo);
 *                     OR
 * $amount = PaginationMysqli::countRows($mysqli);
 **/

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Easy-pagination v0.1</title>
</head>
<style>
    form{
        display: flex;
        justify-content: center;
    }</style>
<body>
<?php

    /**
     * $elements = PaginationPDO::getElements($mysqli, ['id', 'src'], array_key_first($_POST));  // id and src - fields to execute
     *                                              OR
     * $elements = PaginationMysqli::getElements($mysqli, ['id', 'src'], array_key_first($_POST));
     **/
    echo '<pre>';
    //print_r($elements);
    echo '</pre>';

?>
<form action="http://easy-pagi/php7.4+/" method="post">
    <button type="submit" name="0">Start</button>
<?php
       for($i=0;$i<$amount;$i++){
            echo "<button type='submit' name='$i'>$i</button>";
        }
    ?>
    <button type="submit" name="<?=$amount;?>">End</button>
</form>

</body>
</html>
