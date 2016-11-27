<?php
namespace xyz;

use sgk\back\TaskResultReviewDBAdaptor;

require_once __DIR__ . '/vendor/autoload.php';

$taskResultReview = TaskResultReviewDBAdaptor::create(3, 3, 4);
$taskResultReview = TaskResultReviewDBAdaptor::read($taskResultReview->getId());
$taskResultReview->setGrade(5);
$taskResultReview = TaskResultReviewDBAdaptor::update($taskResultReview);
TaskResultReviewDBAdaptor::delete($taskResultReview);
$allTaskResultReviews = TaskResultReviewDBAdaptor::readAll();

foreach ($allTaskResultReviews as $taskResultReview) {
    var_dump($taskResultReview);
}
