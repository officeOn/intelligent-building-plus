<?php
/**
 * The TaskResultReviewResponseBodyGenerator file.
 */
namespace sgk\back;

/**
 * Class TaskResultReviewResponseBodyGenerator.
 *
 * @author Daniil Belyakov <dnl.blkv@gmail.com>
 * @since 20161126 Initial creation.
 */
class TaskResultReviewResponseBodyGenerator
{
    /**
     * @param TaskResultReviewModel[] $taskResultReviews
     *
     * @return array
     */
    public static function generateBulkBody(array $taskResultReviews)
    {
        $bulkBody = [];

        foreach ($taskResultReviews as $taskResultReview) {
            $bulkBody[] = static::generateBody($taskResultReview);
        }

        return $bulkBody;
    }

    /**
     * @param TaskResultReviewModel $taskResultReview
     *
     * @return array
     */
    public static function generateBody(TaskResultReviewModel $taskResultReview)
    {
        return [
            'id' => $taskResultReview->getId(),
            'userGradedById' => $taskResultReview->getUserGradedById(),
            'taskId' => $taskResultReview->getTaskId(),
            'grade' => $taskResultReview->getGrade(),
        ];
    }
}
