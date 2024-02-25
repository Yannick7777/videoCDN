<?php
include "phpFunctions/showVideos.php";
include "phpFunctions/show404.php";

$videoId = $_GET['id'] ?? null;
$friend = $_GET['f'] ?? null;
$originalQuality = $_GET['oq'] ?? false;

if ($friend != null) {
    $usersJson = file_get_contents('friends.json');
    $data = json_decode($usersJson, true);
    $allUsers = array_column($data, 'username');
    $friendFound = in_array($friend, $allUsers);
    if ($friendFound) {
        $videoCompressedDirectory = "friends/$friend/videos/compressed";
        $videoOriginalDirectory = "friends/$friend/videos/original";
        $thumbnailPath = "friends/$friend/thumbnails/$videoId.jpg";
    } else {
        show404();
    }
} else {
    $videoCompressedDirectory = 'videos/compressed';
    $videoOriginalDirectory = 'videos/original';
    $thumbnailPath = "thumbnails/$videoId.jpg";
}

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
        if (!file_exists($thumbnailPath)) {
            exec("ffmpeg -i $inputVideoPath -ss 00:00:05 -vframes 1 -an -y $thumbnailPath");
        }
        shell_exec($cmd . " > /dev/null &"); // Execute the script while continuing the php
        sleep(3);
        header("Refresh:0"); // Refresh page
    } else {show404();}
}