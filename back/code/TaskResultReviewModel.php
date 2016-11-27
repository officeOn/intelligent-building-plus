<?php
/**
 * The TaskResultReviewModel file.
 */
namespace sgk\back;

/**
 * Class TaskResultReviewModel.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class TaskResultReviewModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userGradedById;

    /**
     * @var int
     */
    private $taskId;

    /**
     * @var int
     */
    private $grade;

    /**
     * @param int $id
     * @param int $userGradedById
     * @param int $taskId
     * @param int $grade
     */
    public function __construct($id, $userGradedById, $taskId, $grade)
    {
        $this->id = $id;
        $this->userGradedById = $userGradedById;
        $this->taskId = $taskId;
        $this->grade = $grade;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserGradedById()
    {
        return $this->userGradedById;
    }

    /**
     * @return int|null
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @return TaskModel
     */
    public function getTask()
    {
        return TaskDBAdaptor::read($this->taskId);
    }

    /**
     * @return int
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param int $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }
}
