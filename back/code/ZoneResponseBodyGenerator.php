<?php
/**
 * The ZoneResponseBodyGenerator file.
 */
namespace sgk\back;

/**
 * Class ZoneResponseBodyGenerator.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class ZoneResponseBodyGenerator
{
    /**
     * @param ZoneModel[] $zones
     *
     * @return array
     */
    public static function generateBulkBody(array $zones)
    {
        $bulkBody = [];

        foreach ($zones as $zone) {
            $bulkBody[] = static::generateBody($zone);
        }

        return $bulkBody;
    }

    /**
     * @param ZoneModel $zone
     *
     * @return array
     */
    public static function generateBody(ZoneModel $zone)
    {
        return [
            'id' => $zone->getId(),
            'name' => $zone->getName(),
            'type' => $zone->getType(),
            'coordinates' => $zone->getCoordinates(),
        ];
    }
}
