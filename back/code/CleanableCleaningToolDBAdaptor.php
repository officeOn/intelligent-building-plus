<?php
/**
 * The CleanableCleaningToolDBAdaptor file.
 */
namespace sgk\back;

/**
 * Class CleanableCleaningToolDBAdaptor.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class CleanableCleaningToolDBAdaptor
{
    /**
     * @param int $cleanableId
     * @param string $cleaningToolType
     *
     * @return CleanableCleaningToolModel
     */
    public static function create($cleanableId, $cleaningToolType)
    {
        Utility::assertIsInt($cleanableId);
        Utility::assertIsString($cleaningToolType);

        $query = static::determineQueryCreate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':cleanableId', $cleanableId);
        $dbHelper->bindString(':cleaningToolType', $cleaningToolType);
        $dbHelper->execute();
        $id = $dbHelper->getLastInsertId();

        return new CleanableCleaningToolModel($id, $cleanableId, $cleaningToolType);
    }

    /**
     * @return string
     */
    private static function determineQueryCreate()
    {
        return <<<TEXT
    INSERT INTO Cleanable_Cleaning_Tool (`cleanableId`, `cleaningToolType`) VALUES (:cleanableId, :cleaningToolType);
TEXT;
    }

    /**
     * @param int $id
     *
     * @return CleanableCleaningToolModel|null
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
            return static::createCleanableCleaningToolFromArray($result);
        }
    }

    /**
     * @return string
     */
    private static function determineQueryRead()
    {
        return <<<TEXT
    SELECT * FROM Cleanable_Cleaning_Tool WHERE id=:id;
TEXT;
    }

    /**
     * @param array $array
     *
     * @return CleanableCleaningToolModel
     */
    private static function createCleanableCleaningToolFromArray(array $array)
    {
        $id = (int)$array['id'];
        $cleanableId = (int)$array['cleanableId'];
        $cleaningToolType = $array['cleaningToolType'];

        return new CleanableCleaningToolModel($id, $cleanableId, $cleaningToolType);
    }

    /**
     * @param int|null $cleanableId
     *
     * @return CleanableCleaningToolModel[]
     */
    public static function readAll($cleanableId = null)
    {
        $query = static::determineQueryReadAll();

        if (is_null($cleanableId)) {
            $query .= ';';
            $dbHelper = new DBHelper($query);
        } else {
            $query .= ' WHERE cleanableId=:cleanableId;';
            $dbHelper = new DBHelper($query);
            $dbHelper->bindInt(':cleanableId', $cleanableId);
        }

        $result = $dbHelper->execute()->fetchAll();
        $allCleanableCleaningTools = [];

        if ($result === false) {
            return null;
        } else {
            foreach ($result as $cleanableCleaningTool) {
                $allCleanableCleaningTools[] = static::createCleanableCleaningToolFromArray($cleanableCleaningTool);
            }
        }

        return $allCleanableCleaningTools;
    }

    /**
     * @return string
     */
    private static function determineQueryReadAll()
    {
        return <<<TEXT
    SELECT * FROM Cleanable_Cleaning_Tool
TEXT;
    }

    /**
     * @param CleanableCleaningToolModel $cleanableCleaningTool
     */
    public static function delete(CleanableCleaningToolModel $cleanableCleaningTool)
    {
        $query = static::determineQueryDelete();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $cleanableCleaningTool->getId());
        $dbHelper->execute();
    }

    /**
     * @return string
     */
    private static function determineQueryDelete()
    {
        return <<<TEXT
    DELETE FROM Cleanable_Cleaning_Tool WHERE id=:id;
TEXT;
    }
}
