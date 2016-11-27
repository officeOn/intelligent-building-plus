<?php
/**
 * The TaskResponseBodyGenerator file.
 */
namespace sgk\back;

/**
 * Class TaskResponseBodyGenerator.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class TaskResponseBodyGenerator
{
    /**
     * @param TaskModel[] $tasks
     *
     * @return array
     */
    public static function generateBulkBody(array $tasks)
    {
        $bulkBody = [];

        foreach ($tasks as $task) {
            $bulkBody[] = static::generateBody($task);
        }

        return $bulkBody;
    }

    /**
     * @param TaskModel $task
     *
     * @return array
     */
    public static function generateBody(TaskModel $task)
    {
        $userCreatedBy = UserDBAdaptor::read($task->getUserCreatedById());

        return [
            'id' => $task->getId(),
            'userAssignedToId' => $task->getUserAssignedToId(),
            'userCreatedBy' => UserResponseBodyGenerator::generateBody($userCreatedBy),
            'relatedItemId' => $task->getRelatedItemId(),
            'type' => $task->getType(),
            'status' => $task->getStatus(),
            'description' => static::generateDescription($task),
            'coordinates' => static::determineCoordinates($task)
        ];
    }

    /**
     * @param TaskModel $task
     *
     * @return string
     */
    private static function generateDescription(TaskModel $task)
    {
        $relatedItem = static::getRelatedItem($task);
        $actionString = ucfirst(strtolower($task->getType()));
        $relatedUnit = $relatedItem->getUnit();
        $relatedZone = $relatedUnit->getZone();

        return
            $actionString . ' ' .
            $relatedItem->getName() . ' in ' .
            $relatedUnit->getName() . ' of ' .
            $relatedZone->getName() . '.';
    }

    /**
     * @param TaskModel $task
     *
     * @return CleanableModel|RefillableModel|null
     */
    private static function getRelatedItem(TaskModel $task)
    {
        $relatedItemId = $task->getRelatedItemId();

        if ($task->getType() === 'REFILL') {
            return RefillableDBAdaptor::read($relatedItemId);
        } else {
            return CleanableDBAdaptor::read($relatedItemId);
        }
    }

    /**
     * @param TaskModel $task
     *
     * @return string
     */
    private static function determineCoordinates(TaskModel $task)
    {
        $relatedItem = static::getRelatedItem($task);

        return $relatedItem->getUnit()->getCoordinates();
    }
}
