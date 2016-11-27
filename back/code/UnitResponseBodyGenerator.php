<?php
/**
 * The UnitResponseBodyGenerator file.
 */
namespace sgk\back;

/**
 * Class UnitResponseBodyGenerator.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class UnitResponseBodyGenerator
{
    /**
     * @param UnitModel[] $units
     *
     * @return array
     */
    public static function generateBulkBody(array $units)
    {
        $bulkBody = [];

        foreach ($units as $unit) {
            $bulkBody[] = static::generateBody($unit);
        }

        return $bulkBody;
    }

    /**
     * @param UnitModel $unit
     *
     * @return array
     */
    public static function generateBody(UnitModel $unit)
    {
        return [
            'id' => $unit->getId(),
            'name' => $unit->getName(),
            'zoneId' => $unit->getZoneId(),
            'coordinates' => $unit->getCoordinates(),
        ];
    }
}
