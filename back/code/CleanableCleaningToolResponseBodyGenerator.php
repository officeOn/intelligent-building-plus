<?php
/**
 * The CleanableCleaningToolResponseBodyGenerator file.
 */
namespace sgk\back;

/**
 * Class CleanableCleaningToolResponseBodyGenerator.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class CleanableCleaningToolResponseBodyGenerator
{
    /**
     * @param CleanableCleaningToolModel[] $cleanableCleaningTools
     *
     * @return array
     */
    public static function generateBulkBody(array $cleanableCleaningTools)
    {
        $bulkBody = [];

        foreach ($cleanableCleaningTools as $cleanableCleaningTool) {
            $bulkBody[] = static::generateBody($cleanableCleaningTool);
        }

        return $bulkBody;
    }

    /**
     * @param CleanableCleaningToolModel $cleanableCleaningTool
     *
     * @return array
     */
    public static function generateBody(CleanableCleaningToolModel $cleanableCleaningTool)
    {
        $cleaningToolType = $cleanableCleaningTool->getCleaningToolType();
        $cleaningToolName = static::determineCleaningToolNameByType($cleaningToolType);

        return [
            'id' => $cleanableCleaningTool->getId(),
            'cleanableId' => $cleanableCleaningTool->getCleanableId(),
            'cleaningToolType' => $cleanableCleaningTool->getCleaningToolType(),
            'cleaningToolName' => $cleaningToolName,
        ];
    }

    /**
     * @param string $cleaningToolType
     *
     * @return string
     */
    private static function determineCleaningToolNameByType($cleaningToolType)
    {
        $cleaningToolName = str_replace('_', ' ', $cleaningToolType);
        $cleaningToolName = strtolower($cleaningToolName);
        $cleaningToolName = ucfirst($cleaningToolName);

        return $cleaningToolName;
    }
}
