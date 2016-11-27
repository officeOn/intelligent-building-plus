<?php
/**
 * The CleanableResponseBodyGenerator file.
 */
namespace sgk\back;

/**
 * Class CleanableResponseBodyGenerator.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class CleanableResponseBodyGenerator
{
    /**
     * @param CleanableModel[] $cleanables
     *
     * @return array
     */
    public static function generateBulkBody(array $cleanables)
    {
        $bulkBody = [];

        foreach ($cleanables as $cleanable) {
            $bulkBody[] = static::generateBody($cleanable);
        }

        return $bulkBody;
    }

    /**
     * @param CleanableModel $cleanable
     *
     * @return array
     */
    public static function generateBody(CleanableModel $cleanable)
    {
        return [
            'id' => $cleanable->getId(),
            'name' => $cleanable->getName(),
            'unitId' => $cleanable->getUnitId(),
            'health' => $cleanable->getHealth(),
        ];
    }
}
