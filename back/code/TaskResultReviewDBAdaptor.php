<?php
/**
 * The TaskResultReviewDBAdaptor file.
 */
namespace sgk\back;

/**
 * Class TaskResultReviewDBAdaptor.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class TaskResultReviewDBAdaptor
{
    /**
     * @param int $userGradedById
     * @param int $taskId
     * @param int $grade
     *
     * @return TaskResultReviewModel
     */
    public static function create($userGradedById, $taskId, $grade)
    {
        Utility::assertIsInt($userGradedById);
        Utility::assertIsInt($taskId);
        Utility::assertIsInt($grade);

        $query = static::determineQueryCreate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':userGradedById', $userGradedById);
        $dbHelper->bindInt(':taskId', $taskId);
        $dbHelper->bindInt(':grade', $grade);
        $dbHelper->execute();
        $id = $dbHelper->getLastInsertId();

        return new TaskResultReviewModel($id, $userGradedById, $taskId, $grade);
    }

    /**
     * @return string
     */
    private static function determineQueryCreate()
    {
        return <<<TEXT
    INSERT INTO Task_Result_Review (`userGradedById`, `taskId`, `grade`) VALUES
    (:userGradedById, :taskId, :grade);
TEXT;
    }

    /**
     * @param int $id
     *
     * @return TaskResultReviewModel|null
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
            return static::createTaskResultReviewFromArray($result);
        }
    }

    /**
     * @return string
     */
    private static function determineQueryRead()
    {
        return <<<TEXT
    SELECT * FROM Task_Result_Review WHERE id=:id;
TEXT;
    }

    /**
     * @param array $array
     *
     * @return TaskResultReviewModel
     */
    private static function createTaskResultReviewFromArray(array $array)
    {
        $id = (int)$array['id'];
        $userGradedById = (int)$array['userGradedById'];
        $taskId = (int)$array['taskId'];
        $grade = (int)$array['grade'];

        return new TaskResultReviewModel($id, $userGradedById, $taskId, $grade);
    }

    /**
     * @param int|null $cleanableId
     *
     * @return TaskResultReviewModel[]
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
        $allTaskResultReviews = [];

        if ($result === false) {
            return null;
        } else {
            foreach ($result as $taskResultReview) {
                $allTaskResultReviews[] = static::createTaskResultReviewFromArray($taskResultReview);
            }
        }

        return $allTaskResultReviews;
    }

    /**
     * @return string
     */
    private static function determineQueryReadAll()
    {
        return <<<TEXT
    SELECT * FROM Task_Result_Review
TEXT;
    }


    /**
     * @param TaskResultReviewModel $taskResultReview
     *
     * @return TaskResultReviewModel
     */
    public static function update(TaskResultReviewModel $taskResultReview)
    {
        $query = self::determineQueryUpdate();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':userGradedById', $taskResultReview->getUserGradedById());
        $dbHelper->bindInt(':taskId', $taskResultReview->getTaskId());
        $dbHelper->bindInt(':grade', $taskResultReview->getGrade());
        $dbHelper->bindInt(':id', $taskResultReview->getId());
        $dbHelper->execute();

        return $taskResultReview;
    }

    /**
     * @return string
     */
    private static function determineQueryUpdate()
    {
        return <<<TEXT
    UPDATE Task_Result_Review SET userGradedById=:userGradedById, taskId=:taskId, grade=:grade WHERE id=:id;
TEXT;
    }

    /**
     * @param TaskResultReviewModel $taskResultReview
     */
    public static function delete(TaskResultReviewModel $taskResultReview)
    {
        $query = static::determineQueryDelete();
        $dbHelper = new DBHelper($query);
        $dbHelper->bindInt(':id', $taskResultReview->getId());
        $dbHelper->execute();
    }

    /**
     * @return string
     */
    private static function determineQueryDelete()
    {
        return <<<TEXT
    DELETE FROM Task_Result_Review WHERE id=:id;
TEXT;
    }
}
