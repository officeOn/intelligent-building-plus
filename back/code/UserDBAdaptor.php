<?php
/**
 * The UserDBAdaptor file.
 */
namespace sgk\back;

/**
 * Class UserDBAdaptor.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class UserDBAdaptor
{
    /**
     * @param string $name
     * @param string $type
     * @param int $usefulReportCount
     * @param int $totalReportCount
     *
     * @return UserModel
     */
    public static function create($name, $type, $usefulReportCount, $totalReportCount)
    {
        Utility::assertIsString($name);
        Utility::assertIsString($type);

        $query = static::determineQueryCreate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $name);
        $dbHelper->bindString(':type', $type);
        $dbHelper->bindInt(':usefulReportCount', $usefulReportCount);
        $dbHelper->bindInt(':totalReportCount', $totalReportCount);
        $dbHelper->execute();
        $id = $dbHelper->getLastInsertId();

        return new UserModel($id, $name, $type, $usefulReportCount, $totalReportCount);
    }

    /**
     * @return string
     */
    private static function determineQueryCreate()
    {
        return <<<TEXT
    INSERT INTO User (`name`, `type`, `usefulReportCount`, `totalReportCount`)
    VALUES (:name, :type, :usefulReportCount, :totalReportCount);
TEXT;
    }

    /**
     * @param int $id
     *
     * @return UserModel|null
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
            return static::createUserFromArray($result);
        }
    }

    /**
     * @return string
     */
    private static function determineQueryRead()
    {
        return <<<TEXT
    SELECT * FROM User WHERE id=:id;
TEXT;
    }

    /**
     * @param array $array
     *
     * @return UserModel
     */
    private static function createUserFromArray(array $array)
    {
        $id = (int)$array['id'];
        $name = $array['name'];
        $type = $array['type'];
        $usefulReportCount = (int)$array['usefulReportCount'];
        $totalReportCount = (int)$array['totalReportCount'];

        return new UserModel($id, $name, $type, $usefulReportCount, $totalReportCount);
    }

    /**
     * @return UserModel[]
     */
    public static function readAll()
    {
        $query = static::determineQueryReadAll();
        $dbHelper = new DBHelper($query);
        $result = $dbHelper->execute()->fetchAll();
        $allUsers = [];

        if ($result === false) {
            return null;
        } else {
            foreach ($result as $user) {
                $allUsers[] = static::createUserFromArray($user);
            }
        }

        return $allUsers;
    }

    /**
     * @return string
     */
    private static function determineQueryReadAll()
    {
        return <<<TEXT
    SELECT * FROM User;
TEXT;
    }

    /**
     * @param UserModel $user
     *
     * @return UserModel
     */
    public static function update(UserModel $user)
    {
        $query = self::determineQueryUpdate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindString(':name', $user->getName());
        $dbHelper->bindString(':type', $user->getType());
        $dbHelper->bindInt(':usefulReportCount', $user->getUsefulReportCount());
        $dbHelper->bindInt(':totalReportCount', $user->getTotalReportCount());
        $dbHelper->bindInt(':id', $user->getId());
        $dbHelper->execute();

        return $user;
    }

    /**
     * @return string
     */
    private static function determineQueryUpdate()
    {
        return <<<TEXT
    UPDATE User SET name=:name, type=:type, usefulReportCount=:usefulReportCount, totalReportCount=:totalReportCount 
    WHERE id=:id;
TEXT;
    }

    /**
     * @param UserModel $user
     */
    public static function delete($user)
    {
        $query = static::determineQueryDelete();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $user->getId());
        $dbHelper->execute();
    }

    /**
     * @return string
     */
    private static function determineQueryDelete()
    {
        return <<<TEXT
    DELETE FROM User WHERE id=:id;
TEXT;
    }
}
