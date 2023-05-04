<?php

namespace common;

use mysqli;

class DbHelper
{
    private const dbName = "mm_site2023";
    private $conn;

    public function __construct(
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
        $sql = "SELECT title FROM pages WHERE url=?";
        $this->conn->begin_transaction();
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $url);
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
}