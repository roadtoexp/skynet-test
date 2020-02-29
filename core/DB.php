<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

class DB
{
    protected PDO $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                'mysql:host='.DB_HOST.';dbname='.DB_NAME,
                DB_USER,
                DB_PASSWORD
            );
        } catch (PDOException $exception) {
            throw new PDOException('Подключение не удалось: ' . $exception->getMessage());
        }
    }
}