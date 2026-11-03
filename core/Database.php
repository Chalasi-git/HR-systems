<?php
class Database {
    private static $pdo;
    public static function pdo() {
        if (!self::$pdo) {
            self::$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
    public static function run($sql, $p = []) { $s = self::pdo()->prepare($sql); $s->execute($p); return $s; }
    public static function all($sql, $p = []) { return self::run($sql, $p)->fetchAll(); }
    public static function one($sql, $p = []) { $r = self::run($sql, $p)->fetch(); return $r ?: null; }
}
