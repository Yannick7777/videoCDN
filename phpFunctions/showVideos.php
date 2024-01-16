<?php
function showVideo($isDoneCompressing, $videoId, $thumbnailPath): void
{

if ($isDoneCompressing) {
    global $videoCompressedDirectory;
    $videoPath = "$videoCompressedDirectory/$videoId.webm";
    $videoPathFullQuality = $videoPath;
    $htmlHead = <<<HTML
    <!-- Add discord embeding header to the html -->
    <meta name="viewport" content="width=device-width">
    <meta property="og:image" content="//cdn.eyer.life/$thumbnailPath">
    <meta property="og:type" content="video.other">
    <meta property="og:video:url" content="//cdn.eyer.life/$videoPath">
    <meta property="og:video:width" content="1280">
    <meta property="og:video:height" content="720">
    <meta property="theme-color" content="#C74600">
    HTML;
    $htmlBody = "";

} else {
    global $videoOriginalDirectory;
    global $videoCompressedDirectory;

    $cachedVideoPath = "$videoCompressedDirectory/$videoId.webm";
    $videoPath = "noCacheProxy.php?externalVideoURL=$cachedVideoPath";
    $videoPathFullQuality = "$videoOriginalDirectory/$videoId.mp4";
    $htmlHead = "";
    $htmlBody = <<<HTML
    <!-- Add message informing the user, that the rendering process is not done yet -->
    <h2>Video is still encoding</h2>
    <p>Discord previews don't work right now because of technical reasons </p>
    <p>You can already watch the rendered video to the point it's rendered or view the video in original quality <a href="$videoPathFullQuality">here</a></p>
    <p>Refresh the page to see the new progress</p>
    HTML;
}

    if (!file_exists("thumbnails/$videoId")) {
        exec("ffmpeg -i $videoPathFullQuality -ss 00:00:05 -vframes 1 -an -y $thumbnailPath");
    }

$html = <<<HTML
<!DOCTYPE html>
<head>
    <title>$videoId</title>
    <link rel="stylesheet" href="//eyer.life/.src/styles/backgroundstyle.css">
    <link rel="stylesheet" href="//eyer.life/.src/styles/mainstyle.css">
    <link rel="icon" type="image/svg+xml" href="//eyer.life/.src/img/favicon.svg">
    $htmlHead
</head>
<style>
    body {
        margin: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        text-align: center;
        }
    .center-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 90vh;
    }
    video {
        max-width: 75vw;
        max-height: 90%;
        width: auto;
        height: auto;
    }
</style>

<body>
<div class="center-container">
$htmlBody
<video onloadstart="this.volume=0.1" id = "video" width="100%" poster="$thumbnailPath" controls>
    <source src="$videoPath" type="video/webm">
</video>
<br>
</div>
</body>
</html>
HTML;

echo $html;
}