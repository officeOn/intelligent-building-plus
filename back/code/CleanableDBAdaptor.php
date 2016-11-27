<?php
/**
 * The CleanableDBAdaptor file.
 */
namespace sgk\back;

/**
 * Class CleanableDBAdaptor.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class CleanableDBAdaptor
{
    /**
     * @param string $name
     * @param int $unitId
     * @param int $health
     *
     * @return CleanableModel
     */
    public static function create($name, $unitId, $health)
    {
        Utility::assertIsString($name);
        Utility::assertIsInt($unitId);
        Utility::assertIsInt($health);

        $query = static::determineQueryCreate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $name);
        $dbHelper->bindInt(':unitId', $unitId);
        $dbHelper->bindInt(':health', $health);
        $dbHelper->execute();
        $id = $dbHelper->getLastInsertId();

        return new CleanableModel($id, $name, $unitId, $health);
    }

    /**
     * @return string
     */
    private static function determineQueryCreate()
    {
        return <<<TEXT
    INSERT INTO Cleanable (`name`, `unitId`, `health`) VALUES (:name, :unitId, :health);
TEXT;
    }

    /**
     * @param int $id
     *
     * @return CleanableModel|null
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
            return static::createCleanableFromArray($result);
        }
    }

    /**
     * @return string
     */
    private static function determineQueryRead()
    {
        return <<<TEXT
    SELECT * FROM Cleanable WHERE id=:id;
TEXT;
    }

    /**
     * @param array $array
     *
     * @return CleanableModel
     */
    private static function createCleanableFromArray(array $array)
    {
        $id = (int)$array['id'];
        $name = $array['name'];
        $unitId = (int)$array['unitId'];
        $health = (int)$array['health'];

        return new CleanableModel($id, $name, $unitId, $health);
    }

    /**
     * @param int|null $unitId
     *
     * @return CleanableModel[]
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
        $allCleanables = [];

        if ($result === false) {
            return null;
        } else {
            foreach ($result as $cleanable) {
                $allCleanables[] = static::createCleanableFromArray($cleanable);
            }
        }

        return $allCleanables;
    }

    /**
     * @return string
     */
    private static function determineQueryReadAll()
    {
        return <<<TEXT
    SELECT * FROM Cleanable
TEXT;
    }

    /**
     * @param CleanableModel $cleanable
     *
     * @return CleanableModel
     */
    public static function update(CleanableModel $cleanable)
    {
        $query = self::determineQueryUpdate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $cleanable->getName());
        $dbHelper->bindInt(':unitId', $cleanable->getUnitId());
        $dbHelper->bindInt(':id', $cleanable->getId());
        $dbHelper->bindInt(':health', $cleanable->getHealth());
        $dbHelper->execute();

        return $cleanable;
    }

    /**
     * @return string
     */
    private static function determineQueryUpdate()
    {
        return <<<TEXT
    UPDATE Cleanable SET name=:name, unitId=:unitId, health=:health WHERE id=:id;
TEXT;
    }

    /**
     * @param CleanableModel $cleanable
     */
    public static function delete($cleanable)
    {
        $query = static::determineQueryDelete();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $cleanable->getId());
        $dbHelper->execute();
    }

    /**
     * @return string
     */
    private static function determineQueryDelete()
    {
        return <<<TEXT
    DELETE FROM Cleanable WHERE id=:id;
TEXT;
    }
}
