<?php

namespace Api;

class Db
{

    /**
     * @var \PDO
     */
    public static $db;
    protected static $is_init = false;

    public static function init()
    {
        if (!static::$is_init) {
            static::$db = null;
            try {
                static::$db = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
                static::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                static::$is_init = true;
            } catch (\PDOException $exception) {
                response("Connection error", 500);
            }
            return static::$db;
        }
    }

    /**
     * Prepares an sql statement for execution
     * @param $sql
     * @return \PDOStatement
     */
    public static function q($sql)
    {
        $stmt = static::$db->prepare($sql);
        return $stmt;
    }

    /**
     * Does what bindParam does, but for columns
     * @param $query : The SQL query
     * @param array $placeholders : The placehoders to look out for
     * @param array $values : The actual values to substitute
     */
    public static function sqlBindColumns(&$query, array $placeholders, array $values)
    {
        foreach ($placeholders as $k => $v) $placeholders[$k] = '/' . preg_quote($v) . '/';
        foreach ($values as $k => $v) $values[$k] = static::pdoQuoteColumn($v);

        $query = preg_replace($placeholders, $values, $query);
    }

    /**
     * Quotes a column name in the `column` format
     * @param string $column
     * @return string
     */
    private static function pdoQuoteColumn($column)
    {
        return '`' . substr(static::$db->quote($column), 1, -1) . '`';
    }

    /**
     * Binds all parameters in one call
     * @param \PDOStatement $stmt The PDO statement to bind to
     * @param array $params The parameters to bind
     * @throws \Exception
     */
    public static function bindParams(\PDOStatement &$stmt, array $params)
    {
        $p = [];
        preg_match_all('/\:[A-Za-z0-9\_]+/', $stmt->queryString, $p);

        $p = $p[0];
        if (count($p) != count($params)) throw new \Exception("Number of parameters must match number of placeholders");
        foreach ($p as $k => $v)
            $stmt->bindParam($v, $params[$k]);
    }

    /**
     * Closes db connection
     */
    public static function closeDB() {
        static::$db = null;
        static::$is_init = false;
    }
}
