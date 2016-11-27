<?php
/**
 * The DBHelper file.
 */
namespace sgk\back;

/**
 * Class DBHelper.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class DBHelper
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var \PDOStatement
     */
    private $statement;

    /**
     * @param string $queryString
     */
    public function __construct($queryString)
    {
        Utility::assertIsString($queryString);

        $this->pdo = new \PDO(
            'mysql:host=85.188.8.254:3306;dbname=smart_building;charset=utf8mb4',
            'peppa',
            'superpeppa'
        );
        $this->statement = $this->pdo->prepare($queryString);
    }

    /**
     * @return \PDOStatement
     */
    public function execute()
    {
        $this->statement->execute();

        return $this->statement;
    }

    /**
     * @param string $paramName
     * @param string $paramValue
     */
    public function bindString($paramName, $paramValue)
    {
        Utility::assertIsString($paramName);
        Utility::assertIsString($paramValue);

        $this->statement->bindParam($paramName, $paramValue, \PDO::PARAM_STR);
    }

    /**
     * @param string $paramName
     * @param int $paramValue
     */
    public function bindInt($paramName, $paramValue)
    {
        Utility::assertIsString($paramName);
        Utility::assertIsInt($paramValue);

        $this->statement->bindParam($paramName, $paramValue, \PDO::PARAM_INT);
    }

    /**
     * @return int
     */
    public function getLastInsertId()
    {
        return (int)$this->pdo->lastInsertId();
    }
}
