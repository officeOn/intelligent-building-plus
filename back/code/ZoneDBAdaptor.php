<?php
/**
 * The ZoneDBAdaptor file.
 */
namespace sgk\back;

/**
 * Class ZoneDBAdaptor.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class ZoneDBAdaptor
{
    /**
     * @param string $name
     * @param string $type
     * @param string $coordinates
     *
     * @return ZoneModel
     */
    public static function create($name, $type, $coordinates)
    {
        Utility::assertIsString($name);
        Utility::assertIsString($type);
        Utility::assertIsString($coordinates);

        $query = <<<TEXT
    INSERT INTO Zone (`name`, `type`, `coordinates`) VALUES (:name, :type, :coordinates);
TEXT;
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $name);
        $dbHelper->bindString(':type', $type);
        $dbHelper->bindString(':coordinates', $coordinates);
        $dbHelper->execute();
        $id = $dbHelper->getLastInsertId();

        return new ZoneModel($id, $name, $type, $coordinates);
    }

    /**
     * @param int $id
     *
     * @return ZoneModel|null
     */
    public static function read($id)
    {
        Utility::assertIsInt($id);

        $query = <<<TEXT
    SELECT * FROM Zone WHERE id=:id;
TEXT;
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $id);
        $result = $dbHelper->execute()->fetch();

        if ($result === false) {
            return null;
        } else {
            return static::createZoneFromArray($result);
        }
    }

    /**
     * @param array $array
     *
     * @return ZoneModel
     */
    private static function createZoneFromArray(array $array)
    {
        $id = (int)$array['id'];
        $name = $array['name'];
        $type = $array['type'];
        $coordinates = $array['coordinates'];

        return new ZoneModel($id, $name, $type, $coordinates);
    }

    /**
     * @return ZoneModel[]
     */
    public static function readAll()
    {
        $query = <<<TEXT
    SELECT * FROM Zone;
TEXT;
        $dbHelper = new DBHelper($query);
        $result = $dbHelper->execute()->fetchAll();
        $allZones = [];

        if ($result === false) {
            return null;
        } else {
            foreach ($result as $zoneBody) {
                $allZones[] = static::createZoneFromArray($zoneBody);
            }
        }

        return $allZones;
    }

    /**
     * @param ZoneModel $zone
     *
     * @return ZoneModel
     */
    public static function update(ZoneModel $zone)
    {
        $query = <<<TEXT
    UPDATE Zone SET name=:name, type=:type, coordinates=:coordinates WHERE id=:id;
TEXT;
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $zone->getName());
        $dbHelper->bindString(':type', $zone->getType());
        $dbHelper->bindString(':coordinates', $zone->getCoordinates());
        $dbHelper->bindInt(':id', $zone->getId());
        $dbHelper->execute();

        return $zone;
    }

    /**
     * @param ZoneModel $zone
     */
    public static function delete(ZoneModel $zone)
    {
        $query = <<<TEXT
    DELETE FROM Zone WHERE id=:id;
TEXT;
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $zone->getId());
        $dbHelper->execute();
    }
}
