<?php
/**
 * The RefillableModel file.
 */
namespace sgk\back;

/**
 * Class RefillableModel.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class RefillableModel
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
    private $count;

    /**
     * @var int
     */
    private $countFull;

    /**
     * @var string
     */
    private $consumableType;

    /**
     * @param int $id
     * @param string $name
     * @param int $unitId
     * @param int $count
     * @param int $countFull
     * @param string $consumableType
     */
    public function __construct($id, $name, $unitId, $count, $countFull, $consumableType)
    {
        $this->id = $id;
        $this->name = $name;
        $this->unitId = $unitId;
        $this->count = $count;
        $this->countFull = $countFull;
        $this->consumableType = $consumableType;
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
    public function setUnit($unitId)
    {
        $this->unitId = $unitId;
    }

    /**
     * @return UnitModel
     */
    public function getUnit()
    {
        return UnitDBAdaptor::read($this->unitId);
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $countFull
     */
    public function setCountFull($countFull)
    {
        $this->countFull = $countFull;
    }

    /**
     * @return int
     */
    public function getCountFull()
    {
        return $this->countFull;
    }

    /**
     * @return string
     */
    public function getConsumableType()
    {
        return $this->consumableType;
    }

    /**
     * @param string $consumableType
     */
    public function setConsumableType($consumableType)
    {
        $this->consumableType = $consumableType;
    }
}
