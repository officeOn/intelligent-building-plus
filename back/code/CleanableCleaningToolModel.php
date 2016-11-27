<?php
/**
 * The CleanableCleaningToolModel file.
 */
namespace sgk\back;

/**
 * Class CleanableCleaningToolModel.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class CleanableCleaningToolModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $cleanableId;

    /**
     * @var string
     */
    private $cleaningToolType;

    /**
     * @param int $id
     * @param int $cleanableId
     * @param string $cleaningToolType
     */
    public function __construct($id, $cleanableId, $cleaningToolType)
    {
        $this->id = $id;
        $this->cleanableId = $cleanableId;
        $this->cleaningToolType = $cleaningToolType;
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
    public function getCleanableId()
    {
        return $this->cleanableId;
    }

    /**
     * @param int $cleanableId
     */
    public function setCleanableId($cleanableId)
    {
        $this->cleanableId = $cleanableId;
    }

    /**
     * @return CleanableModel
     */
    public function getCleanable()
    {
        return CleanableDBAdaptor::read($this->cleanableId);
    }

    /**
     * @return string
     */
    public function getCleaningToolType()
    {
        return $this->cleaningToolType;
    }

    /**
     * @param string $cleaningToolType
     */
    public function setCleaningToolType($cleaningToolType)
    {
        $this->cleaningToolType = $cleaningToolType;
    }
}
