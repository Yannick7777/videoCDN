<?php
include "phpFunctions/showVideos.php";
include "phpFunctions/show404.php";

$videoId = $_GET['id'] ?? null; // Get Video ID

$videoCompressedDirectory = 'videos/compressed';
$videoOriginalDirectory = 'videos/original';
$thumbnailPath = "thumbnails/$videoId.jpg";

$availableCompressedVideos = array_diff(scandir($videoCompressedDirectory), ['.', '..']); // Get available compressed videos

if ($videoId && in_array($videoId . '.webm', $availableCompressedVideos)) {
    if (exec("(ps aux | grep -c '$videoId.mp4')") > 3) { // Test for running encoding tasks
        showVideo(false, $videoId, $thumbnailPath);
        exit;
    }
    showVideo(true, $videoId, $thumbnailPath);

} else {
    $availableOriginalVideos = array_diff(scandir($videoOriginalDirectory), ['.', '..']); // Get available original videos
    if ($videoId && in_array($videoId . '.mp4', $availableOriginalVideos)) {
        $inputVideoPath = "$videoOriginalDirectory/$videoId.mp4";
        $outputVideoPath = "$videoCompressedDirectory/$videoId.webm";
        $cmd = "bash encode.sh $inputVideoPath $outputVideoPath"; // Set command for executing the encoding script
        exec($cmd . " > /dev/null &"); // Execute the script while continuing the php
        sleep(3);
        header("Refresh:0"); // Refresh page
    } else {show404();}
}