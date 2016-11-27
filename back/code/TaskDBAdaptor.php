<?php
/**
 * The TaskDBAdaptor file.
 */
namespace sgk\back;

/**
 * Class TaskDBAdaptor.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class TaskDBAdaptor
{
    /**
     * @param int $userCreatedById
     * @param int $userAssignedToId
     * @param int $relatedItemId
     * @param string $type
     * @param string $status
     *
     * @return TaskModel
     */
    public static function create($userCreatedById, $userAssignedToId, $relatedItemId, $type, $status)
    {
        Utility::assertIsInt($userCreatedById);
        Utility::assertIsInt($userAssignedToId);
        Utility::assertIsInt($relatedItemId);
        Utility::assertIsString($type);
        Utility::assertIsString($status);

        $query = static::determineQueryCreate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':userCreatedById', $userCreatedById);
        $dbHelper->bindInt(':userAssignedToId', $userAssignedToId);
        $dbHelper->bindInt(':relatedItemId', $relatedItemId);
        $dbHelper->bindString(':type', $type);
        $dbHelper->bindString(':status', $status);
        $dbHelper->execute();
        $id = $dbHelper->getLastInsertId();

        return new TaskModel($id, $userCreatedById, $userAssignedToId, $relatedItemId, $type, $status);
    }

    /**
     * @return string
     */
    private static function determineQueryCreate()
    {
        return <<<TEXT
    INSERT INTO Task (`userCreatedById`, `userAssignedToId`, `relatedItemId`, `type`, `status`)
    VALUES (:userCreatedById, :userAssignedToId, :relatedItemId, :type, :status);
TEXT;
    }

    /**
     * @param int $id
     *
     * @return TaskModel|null
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
            return static::createTaskFromArray($result);
        }
    }

    /**
     * @return string
     */
    private static function determineQueryRead()
    {
        return <<<TEXT
    SELECT * FROM Task WHERE id=:id;
TEXT;
    }

    /**
     * @param array $array
     *
     * @return TaskModel
     */
    private static function createTaskFromArray(array $array)
    {
        $id = (int)$array['id'];
        $userCreatedById = (int)$array['userCreatedById'];
        $userAssignedToId = (int)$array['userAssignedToId'];
        $relatedItemId = (int)$array['relatedItemId'];
        $type = $array['type'];
        $status = $array['status'];

        return new TaskModel($id, $userCreatedById, $userAssignedToId, $relatedItemId, $type, $status);
    }

    /**
     * @param int|null $userCreatedById
     *
     * @return TaskModel[]
     */
    public static function readAll($userCreatedById = null)
    {
        $query = static::determineQueryReadAll();

        if (is_null($userCreatedById)) {
            $query .= ';';
            $dbHelper = new DBHelper($query);
        } else {
            $query .= ' WHERE userCreatedById=:userCreatedById;';
            $dbHelper = new DBHelper($query);
            $dbHelper->bindInt(':userCreatedById', $userCreatedById);
        }

        $result = $dbHelper->execute()->fetchAll();
        $allTasks = [];

        if ($result === false) {
            return null;
        } else {
            foreach ($result as $task) {
                $allTasks[] = static::createTaskFromArray($task);
            }
        }

        return $allTasks;
    }

    /**
     * @return string
     */
    private static function determineQueryReadAll()
    {
        return <<<TEXT
    SELECT * FROM Task
TEXT;
    }

    /**
     * @param TaskModel $task
     *
     * @return TaskModel
     */
    public static function update(TaskModel $task)
    {
        $query = self::determineQueryUpdate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':userCreatedById', $task->getUserCreatedById());
        $dbHelper->bindInt(':userAssignedToId', $task->getUserAssignedToId());
        $dbHelper->bindInt(':relatedItemId', $task->getRelatedItemId());
        $dbHelper->bindString(':type', $task->getType());
        $dbHelper->bindString(':status', $task->getStatus());
        $dbHelper->bindInt(':id', $task->getId());
        $dbHelper->execute();

        return $task;
    }

    /**
     * @return string
     */
    private static function determineQueryUpdate()
    {
        return <<<TEXT
    UPDATE Task SET userCreatedById=:userCreatedById, userAssignedToId=:userAssignedToId, relatedItemId=:relatedItemId,
    type=:type, status=:status WHERE id=:id;
TEXT;
    }

    /**
     * @param TaskModel $task
     */
    public static function delete(TaskModel $task)
    {
        $query = static::determineQueryDelete();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $task->getId());
        $dbHelper->execute();
    }

    /**
     * @return string
     */
    private static function determineQueryDelete()
    {
        return <<<TEXT
    DELETE FROM Task WHERE id=:id;
TEXT;
    }
}
