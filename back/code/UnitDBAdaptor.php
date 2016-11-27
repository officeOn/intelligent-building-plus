<?php
/**
 * The UnitDBAdaptor file.
 */
namespace sgk\back;

/**
 * Class UnitDBAdaptor.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class UnitDBAdaptor
{
    /**
     * @param string $name
     * @param int $zoneId
     * @param string $coordinates
     *
     * @return UnitModel
     */
    public static function create($name, $zoneId, $coordinates)
    {
        Utility::assertIsString($name);
        Utility::assertIsInt($zoneId);
        Utility::assertIsString($coordinates);

        $query = static::determineQueryCreate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $name);
        $dbHelper->bindInt(':zoneId', $zoneId);
        $dbHelper->bindString(':coordinates', $coordinates);
        $dbHelper->execute();
        $id = $dbHelper->getLastInsertId();

        return new UnitModel($id, $name, $zoneId, $coordinates);
    }

    /**
     * @return string
     */
    private static function determineQueryCreate()
    {
        return <<<TEXT
    INSERT INTO Unit (`name`, `zoneId`, `coordinates`) VALUES (:name, :zoneId, :coordinates);
TEXT;
    }

    /**
     * @param int $id
     *
     * @return UnitModel|null
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
            return static::createUnitFromArray($result);
        }
    }

    /**
     * @return string
     */
    private static function determineQueryRead()
    {
        return <<<TEXT
    SELECT * FROM Unit WHERE id=:id;
TEXT;
    }

    /**
     * @param array $array
     *
     * @return UnitModel
     */
    private static function createUnitFromArray(array $array)
    {
        $id = (int)$array['id'];
        $name = $array['name'];
        $zoneId = (int)$array['zoneId'];
        $coordinates = $array['coordinates'];

        return new UnitModel($id, $name, $zoneId, $coordinates);
    }

    /**
     * @param int|null $zoneId
     *
     * @return UnitModel[]
     */
    public static function readAll($zoneId = null)
    {
        $query = static::determineQueryReadAll();

        if (is_null($zoneId)) {
            $query .= ';';
            $dbHelper = new DBHelper($query);
        } else {
            $query .= ' WHERE zoneId=:zoneId;';
            $dbHelper = new DBHelper($query);
            $dbHelper->bindInt(':zoneId', $zoneId);
        }

        $result = $dbHelper->execute()->fetchAll();
        $allUnits = [];

        if ($result === false) {
            return null;
        } else {
            foreach ($result as $unit) {
                $allUnits[] = static::createUnitFromArray($unit);
            }
        }

        return $allUnits;
    }

    /**
     * @return string
     */
    private static function determineQueryReadAll()
    {
        return <<<TEXT
    SELECT * FROM Unit
TEXT;
    }

    /**
     * @param UnitModel $unit
     *
     * @return UnitModel
     */
    public static function update(UnitModel $unit)
    {
        $query = self::determineQueryUpdate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $unit->getName());
        $dbHelper->bindInt(':zoneId', $unit->getZoneId());
        $dbHelper->bindInt(':id', $unit->getId());
        $dbHelper->bindString(':coordinates', $unit->getCoordinates());
        $dbHelper->execute();

        return $unit;
    }

    /**
     * @return string
     */
    private static function determineQueryUpdate()
    {
        return <<<TEXT
    UPDATE Unit SET name=:name, zoneId=:zoneId, coordinates=:coordinates WHERE id=:id;
TEXT;
    }

    /**
     * @param UnitModel $unit
     */
    public static function delete(UnitModel $unit)
    {
        $query = static::determineQueryDelete();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $unit->getId());
        $dbHelper->execute();
    }

    /**
     * @return string
     */
    private static function determineQueryDelete()
    {
        return <<<TEXT
    DELETE FROM Unit WHERE id=:id;
TEXT;
    }

    /**
     * @param int $unitId
     *
     * @return int
     */
    public static function getUnitHealth($unitId)
    {
        Utility::assertIsInt($unitId);

        $query = static::determineQueryGetUnitHealth();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':unitId', $unitId);
        $result = $dbHelper->execute()->fetch();

        return $result['health'];
    }

    /**
     * @return string
     */
    private static function determineQueryGetUnitHealth()
    {
        return <<<TEXT
    SELECT MIN(health) as 'health' FROM Cleanable where unitId=:unitId;
TEXT;
    }
}
