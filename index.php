<?php
include "phpFunctions/showVideos.php";
include "phpFunctions/show404.php";

$videoId = $_GET['id'] ?? null;

$videoCompressedDirectory = 'videos/compressed';
$videoOriginalDirectory = 'videos/original';
$thumbnailPath = "thumbnails/$videoId.jpg";
$availableCompressedVideos = array_diff(scandir($videoCompressedDirectory), ['.', '..']);

if ($videoId && in_array($videoId . '.webm', $availableCompressedVideos)) {
    if (exec("(ps aux | grep -c '$videoId.mp4')") > 3) {
        showVideo(false, $videoId, $thumbnailPath);
        exit;
    }
    showVideo(true, $videoId, $thumbnailPath);

} else {
    $availableOriginalVideos = array_diff(scandir($videoOriginalDirectory), ['.', '..']);
    if ($videoId && in_array($videoId . '.mp4', $availableOriginalVideos)) {
        $inputVideoPath = "$videoOriginalDirectory/$videoId.mp4";
        $outputVideoPath = "$videoCompressedDirectory/$videoId.webm";
        $cmd = "bash encode.sh $inputVideoPath $outputVideoPath";
        exec($cmd . " > /dev/null &");
        sleep(3);
        header("Refresh:0");
    } else {show404();}
}