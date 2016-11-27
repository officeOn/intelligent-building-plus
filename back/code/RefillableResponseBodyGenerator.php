<?php
/**
 * The RefillableResponseBodyGenerator file.
 */
namespace sgk\back;

/**
 * Class RefillableResponseBodyGenerator.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class RefillableResponseBodyGenerator
{
    /**
     * @param RefillableModel[] $refillables
     *
     * @return array
     */
    public static function generateBulkBody(array $refillables)
    {
        $bulkBody = [];

        foreach ($refillables as $refillable) {
            $bulkBody[] = static::generateBody($refillable);
        }

        return $bulkBody;
    }

    /**
     * @param RefillableModel $refillable
     *
     * @return array
     */
    public static function generateBody(RefillableModel $refillable)
    {
        return [
            'id' => $refillable->getId(),
            'name' => $refillable->getName(),
            'count' => $refillable->getCount(),
            'countFull' => $refillable->getCountFull(),
            'unitId' => $refillable->getUnitId(),
            'consumableType' => $refillable->getConsumableType(),
        ];
    }
}
