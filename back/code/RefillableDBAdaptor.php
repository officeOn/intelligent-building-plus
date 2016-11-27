<?php
/**
 * The RefillableDBAdaptor file.
 */
namespace sgk\back;

/**
 * Class RefillableDBAdaptor.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class RefillableDBAdaptor
{
    /**
     * @param string $name
     * @param int $unitId
     * @param int $count
     * @param int $countFull
     * @param string $consumableType
     *
     * @return RefillableModel
     */
    public static function create($name, $unitId, $count, $countFull, $consumableType)
    {
        Utility::assertIsString($name);

        $query = static::determineQueryCreate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $name);
        $dbHelper->bindInt(':unitId', $unitId);
        $dbHelper->bindInt(':count', $count);
        $dbHelper->bindInt(':countFull', $countFull);
        $dbHelper->bindString(':consumableType', $consumableType);
        $dbHelper->execute();
        $id = $dbHelper->getLastInsertId();

        return new RefillableModel($id, $name, $unitId, $count, $countFull, $consumableType);
    }

    /**
     * @return string
     */
    private static function determineQueryCreate()
    {
        return <<<TEXT
    INSERT INTO Refillable (`name`, `unitId`, `count`, `countFull`, `consumableType`)
    VALUES (:name, :unitId, :count, :countFull, :consumableType);
TEXT;
    }

    /**
     * @param int $id
     *
     * @return RefillableModel|null
     */
    public static function read($id)
    {
        Utility::assertIsInt($id);

        $query = static::determineQueryRead();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $id);
        $result = $dbHelper->execute()->fetch();

        if ($result === false) {
            return null;
        } else {
            return static::createRefillableFromArray($result);
        }
    }

    /**
     * @return string
     */
    private static function determineQueryRead()
    {
        return <<<TEXT
    SELECT * FROM Refillable WHERE id=:id;
TEXT;
    }

    /**
     * @param array $array
     *
     * @return RefillableModel
     */
    private static function createRefillableFromArray(array $array)
    {
        $id = (int)$array['id'];
        $name = $array['name'];
        $unitId = (int)$array['unitId'];
        $count = (int)$array['count'];
        $countFull = (int)$array['countFull'];
        $consumableType = $array['consumableType'];

        return new RefillableModel($id, $name, $unitId, $count, $countFull, $consumableType);
    }

    /**
     * @param int|null $unitId
     *
     * @return RefillableModel[]
     */
    public static function readAll($unitId = null)
    {
        $query = static::determineQueryReadAll();

        if (is_null($unitId)) {
            $query .= ';';
            $dbHelper = new DBHelper($query);
        } else {
            $query .= ' WHERE unitId=:unitId;';
            $dbHelper = new DBHelper($query);
            $dbHelper->bindInt(':unitId', $unitId);
        }

        $result = $dbHelper->execute()->fetchAll();
        $allRefillables = [];

        if ($result === false) {
            return null;
        } else {
            foreach ($result as $refillable) {
                $allRefillables[] = static::createRefillableFromArray($refillable);
            }
        }

        return $allRefillables;
    }

    /**
     * @return string
     */
    private static function determineQueryReadAll()
    {
        return <<<TEXT
    SELECT * FROM Refillable
TEXT;
    }

    /**
     * @param RefillableModel $refillable
     *
     * @return RefillableModel
     */
    public static function update(RefillableModel $refillable)
    {
        $query = self::determineQueryUpdate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $refillable->getName());
        $dbHelper->bindInt(':unitId', $refillable->getUnitId());
        $dbHelper->bindInt(':id', $refillable->getId());
        $dbHelper->bindInt(':count', $refillable->getCount());
        $dbHelper->bindInt(':countFull', $refillable->getCountFull());
        $dbHelper->bindString(':consumableType', $refillable->getConsumableType());
        $dbHelper->execute();

        return $refillable;
    }

    /**
     * @return string
     */
    private static function determineQueryUpdate()
    {
        return <<<TEXT
    UPDATE Refillable SET name=:name, unitId=:unitId, count=:count, 
    countFull=:countFull, consumableType=:consumableType WHERE id=:id;
TEXT;
    }

    /**
     * @param RefillableModel $refillable
     */
    public static function delete(RefillableModel $refillable)
    {
        $query = static::determineQueryDelete();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $refillable->getId());
        $dbHelper->execute();
    }

    /**
     * @return string
     */
    private static function determineQueryDelete()
    {
        return <<<TEXT
    DELETE FROM Refillable WHERE id=:id;
TEXT;
    }
}
