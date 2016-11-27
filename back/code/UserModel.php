<?php
/**
 * The UserModel file.
 */
namespace sgk\back;

/**
 * Class UserModel.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class UserModel
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
     * @var int
     */
    private $usefulReportCount;

    /**
     * @var int
     */
    private $totalReportCount;

    /**
     * @param int $id
     * @param string $name
     * @param string $type
     * @param int $usefulReportCount
     * @param int $totalReportCount
     */
    public function __construct($id, $name, $type, $usefulReportCount, $totalReportCount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->usefulReportCount = $usefulReportCount;
        $this->totalReportCount = $totalReportCount;
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
     * @return int
     */
    public function getUsefulReportCount()
    {
        return $this->usefulReportCount;
    }

    /**
     * @param int $usefulReportCount
     */
    public function setUsefulReportCount($usefulReportCount)
    {
        $this->usefulReportCount = $usefulReportCount;
    }

    /**
     * @return int
     */
    public function getTotalReportCount()
    {
        return $this->totalReportCount;
    }

    /**
     * @param int $totalReportCount
     */
    public function setTotalReportCount($totalReportCount)
    {
        $this->totalReportCount = $totalReportCount;
    }
}
