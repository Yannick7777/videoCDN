<?php
$externalVideoUrl = $_GET['externalVideoURL'] ?? null;
header("Content-Type: video/webm"); // Adjust the content type based on the video format
header("Cache-Control: no-cache, no-store"); // Set chache headers
$externalVideoContent = file_get_contents($externalVideoUrl);
echo $externalVideoContent;