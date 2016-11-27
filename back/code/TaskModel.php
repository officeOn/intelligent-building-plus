<?php
/**
 * The TaskModel file.
 */
namespace sgk\back;

/**
 * Class TaskModel.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class TaskModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userCreatedById;

    /**
     * @var int
     */
    private $userAssignedToId;

    /**
     * @var int
     */
    private $relatedItemId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $status;

    /**
     * @param int $id
     * @param int $userCreatedById
     * @param int $userAssignedToId
     * @param int $relatedItemId
     * @param string $type
     * @param string $status
     */
    public function __construct($id, $userCreatedById, $userAssignedToId, $relatedItemId, $type, $status)
    {
        $this->id = $id;
        $this->userCreatedById = $userCreatedById;
        $this->userAssignedToId = $userAssignedToId;
        $this->relatedItemId = $relatedItemId;
        $this->type = $type;
        $this->status = $status;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getUserCreatedById()
    {
        return $this->userCreatedById;
    }

    /**
     * @return UserModel|null
     */
    public function getUserCreatedBy()
    {
        return UserDBAdaptor::read($this->userCreatedById);
    }

    /**
     * @return int
     */
    public function getUserAssignedToId()
    {
        return $this->userAssignedToId;
    }

    /**
     * @param int $userAssignedToId
     */
    public function setUserAssignedToId($userAssignedToId)
    {
        $this->userAssignedToId = $userAssignedToId;
    }

    /**
     * @return UserModel|null
     */
    public function getUserAssignedTo()
    {
        return UserDBAdaptor::read($this->userAssignedToId);
    }

    /**
     * @return int
     */
    public function getRelatedItemId()
    {
        return $this->relatedItemId;
    }

    /**
     * @param int $relatedItemId
     */
    public function setRelatedItemId($relatedItemId)
    {
        $this->relatedItemId = $relatedItemId;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
