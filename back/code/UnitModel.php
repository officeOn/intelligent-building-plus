<?php
/**
 * The UnitModel file.
 */
namespace sgk\back;

/**
 * Class UnitModel.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class UnitModel
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
    private $zoneId;

    /**
     * @var string
     */
    private $coordinates;

    /**
     * @param int $id
     * @param string $name
     * @param int $zoneId
     * @param string $coordinates
     */
    public function __construct($id, $name, $zoneId, $coordinates)
    {
        $this->id = $id;
        $this->name = $name;
        $this->zoneId = $zoneId;
        $this->coordinates = $coordinates;
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
    public function getZoneId()
    {
        return $this->zoneId;
    }

    /**
     * @param int $zoneId
     */
    public function setZone($zoneId)
    {
        $this->zoneId = $zoneId;
    }

    /**
     * @return ZoneModel|null
     */
    public function getZone()
    {
        return ZoneDBAdaptor::read($this->zoneId);
    }

    /**
     * @param string $coordinates
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @return string
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }
}
