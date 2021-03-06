<?php
/**
 * @file
 * Use ageitgey's face recognition API to detect faces in our dataset.
 */

// Include custom classes.
require_once __DIR__ . '/../../FaceDetectionClient.php';
require_once __DIR__ . '/../../FaceDetectionImage.php';
require_once __DIR__ . '/../../FaceDetectionShell.php';

// Init our FaceDetectionClient class.
$app = new FaceDetection\FaceDetectionClient(basename(__DIR__), 'Ageitgey - Face Recognition', [255, 255, 0]);

// Initialize our client.
$client = new FaceDetection\FaceDetectionShell('face_detection');

// Load our dataset.
$images = $app->loadImages();

// Detect faces in our dataset.
foreach ($images as &$image) {
  $app->startTimer();
  $faces = $client->detectFacesFromList($image);
  $image->setProcessingTime($app->stopTimer());

  if (!empty($faces)) {
    foreach ($faces as $face) {
      $x1 = $face['left'];
      $y1 = $face['top'];
      $x2 = $face['right'];
      $y2 = $face['bottom'];
      $image->drawBoundingBox($x1, $y1, $x2, $y2);
      $image->increaseDetectedFaceCount();
    }
  }

  // Save our image.
  $image->save();
}

// Add analytical data to our CSV file.
$app->exportCSV();

print 'Finished parsing dataset, found [' . $app->getTotalDetectedFaceCount() . '] faces.' . "\n";
