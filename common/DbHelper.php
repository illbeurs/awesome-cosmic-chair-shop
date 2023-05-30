<?php

namespace common;

use Exception;
use mysqli;

class DbHelper
{
    private const dbName = "mm_site2023";
    private static ?DbHelper $instance = null;
    private $conn;

    public static function getInstance($host = null, $port = null, $user = null, $pass = null): DbHelper {
        if (self::$instance === null) self::$instance = new DbHelper($host, $port, $user, $pass);
        return self::$instance;
    }

    private function __construct(
        $host, $port, $user, $pass
    ){
        $this->conn = new mysqli();
        $this->conn->connect(
            hostname: $host,
            username: $user,
            password: $pass,
            database: self::dbName,
            port: $port
        );
    }

    public function getTitle($url): string{
        $sql = "SELECT title FROM pages WHERE url=? or alias=?";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $url, $url);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $stmt->close();
        $this->conn->commit();
        return ($row !== null && $row !== false) ? $row[0] : "";
    }

    public function getPagesInfo(): array{
        $sql = "SELECT * FROM pages";
        $this->conn->begin_transaction();
        $result = $this->conn->query($sql);
        $res_arr = $result->fetch_all(MYSQLI_ASSOC);
        $result->free_result();
        $this->conn->commit();
        return $res_arr;
    }

    public function getUserPassword(string $user): ?string{
        $sql = "SELECT password FROM users WHERE login = ?";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $this->conn->commit();
        return ($row === null) ? $row : $row['password'];
    }

    public function isSecure(string $page){
        $sql = "SELECT secure FROM pages WHERE url=? or alias=?";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $page, $page);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $this->conn->commit();
        return $row !== null && $row['secure'] == 1;
    }

    public function getUserName(string $user){
        $sql = "SELECT `name` FROM users WHERE login = ?";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $this->conn->commit();
        return ($row === null) ? $row : $row['name'];
    }

    public function saveUser(string $login, string $password, string $name): bool
    {
        $sql = "INSERT INTO `users` (login, password, name) VALUES(?, ?, ?)";
        try {
            $this->conn->begin_transaction();
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $login, $password, $name);
            if (!$stmt->execute()) throw new Exception("Ошибка добавления пользователя");
            $this->conn->commit();
            return true;
        } catch (\Throwable $ex){
            $this->conn->rollback();
            return false;
        }
    }
    public function getProductInfo(int $prodId, string $colname)
    {
        $sql = "SELECT $colname FROM products WHERE id = ?";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $prodId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $this->conn->commit();
        return ($row[$colname]);
    }
    public function updateProduct(int $prodId, int $amount) : void
    {
        $sql = "UPDATE `products` SET `amount`= ?  WHERE id = ?";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $amount, $prodId);
        $stmt->execute();
        $stmt->close();
        $this->conn->commit();
    }
    public function updateCart(string $login, string $prodname)
    { 
        $sql = "SELECT COUNT(*) FROM `cart` WHERE login ='$login' and prod_name = '$prodname'";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $this->conn->commit();
        $count = intval($row['COUNT(*)']);
        if (!$count){
            $sql = "INSERT INTO `cart` (login, prod_name, count) VALUES ('$login', '$prodname', '1')";
            $this->conn->begin_transaction();
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $stmt->close();
            $this->conn->commit();
        }
        else {
        $sql = "UPDATE `cart` SET `count` = count + 1
        WHERE login ='$login' and prod_name = '$prodname' ";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->close();
        $this->conn->commit();
        }
    }
    public function selectCart(string $login)
    {
        $sql = "SELECT c.prod_name, c.count, p.price * c.count FROM cart c JOIN products p on c.prod_name = p.name WHERE c.login ='$login'";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_all();
        $stmt->close();
        $this->conn->commit();
        return $row;
    }
    public function delProdFromCart(string $login, string $prod_name)
    {
        $sql = "DELETE FROM `cart` WHERE login = ? and prod_name = ?";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $login, $prod_name);
        $stmt->execute();
        $stmt->close();
        $this->conn->commit();
    }
    public function buyProd(string $login, string $prod_name, int $count) : bool
    {
        if ($count > $this->getProductInfo(($prod_name == "Млечный стул") ? 1:2, 'amount'))
        {
            return false;
        }
        else{
        $this->delProdFromCart($login, $prod_name); 
        $sql = "UPDATE `products` SET `amount` = `amount` - $count
        WHERE name = '$prod_name'";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $stmt->close();
        $this->conn->commit();
        return true;
        }
    }
    public function getCartAmount(string $login, string $prod_name): int
    {
        $sql = "SELECT `count` FROM cart WHERE login ='$login' and prod_name = '$prod_name' ";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $this->conn->commit();
        return ($row === null) ? 0 : intval($row['count']);
    }


}