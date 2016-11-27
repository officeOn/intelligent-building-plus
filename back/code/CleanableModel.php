<?php
/**
 * The CleanableModel file.
 */
namespace sgk\back;

/**
 * Class CleanableModel.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class CleanableModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $unitId;

    /**
     * @var int
     */
    private $health;

    /**
     * @param int $id
     * @param string $name
     * @param int $unitId
     * @param int $health
     */
    public function __construct($id, $name, $unitId, $health)
    {
        $this->id = $id;
        $this->name = $name;
        $this->unitId = $unitId;
        $this->health = $health;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int|null
     */
    public function getUnitId()
    {
        return $this->unitId;
    }

    /**
     * @param int $unitId
     */
    public function setUnitId($unitId)
    {
        $this->unitId = $unitId;
    }

    /**
     * @return UnitModel|null
     */
    public function getUnit()
    {
        return UnitDBAdaptor::read($this->unitId);
    }

    /**
     * @param int $health
     */
    public function setHealth($health)
    {
        $this->health = $health;
    }

    /**
     * @return int
     */
    public function getHealth()
    {
        return $this->health;
    }
}
