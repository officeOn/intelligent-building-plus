<?php
/**
 * The ZoneModel file.
 */
namespace sgk\back;

/**
 * Class ZoneModel.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class ZoneModel
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
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $coordinates;

    /**
     * @param int $id
     * @param string $name
     * @param string $type
     * @param string $coordinates
     */
    public function __construct($id, $name, $type, $coordinates)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param string $coordinates
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
    }
}
