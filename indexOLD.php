<?php
$videoId = $_GET['id'] ?? null;

$videoDirectory = 'videos';
$availableVideos = array_diff(scandir($videoDirectory), ['.', '..']);

if ($videoId && in_array($videoId . '.mp4', $availableVideos)) {
    $videoPath = "$videoDirectory/$videoId.mp4";
    $thumbnailPath = "thumbnails/$videoId.jpg";

    if (!file_exists($thumbnailPath)) {
        exec("ffmpeg -i $videoPath -ss 00:00:05 -vframes 1 -an -y $thumbnailPath");
    }

    $html = <<<HTML
<!DOCTYPE html>
</style>
<head>
       <title>$videoId</title>
       <meta name="viewport" content="width=device-width">
       <meta property="og:image" content="//cdn.eyer.life/$thumbnailPath">
       <meta property="og:type" content="video.other">
       <meta property="og:video:url" content="//cdn.eyer.life/$videoPath">
       <meta property="og:video:width" content="1280">
       <meta property="og:video:height" content="720">
       <meta property="theme-color" content="#C74600">
       <link rel="stylesheet" href="//eyer.life/.src/styles/backgroundstyle.css">
       <link rel="icon" type="image/svg+xml" href="//eyer.life/.src/img/favicon.svg">


</head>

<style>
    body {
      margin: 0;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh; 
    }

    video {
      max-width: 80vw; 
      max-height: 100%;
      width: auto;
      height: auto;
    }
</style>

<body>
<video width="100%" controls="" onloadstart="this.volume=0.1" poster="$thumbnailPath">
       <source src="$videoPath" type="video/mp4">
</video>

</body>
</html>
HTML;

    echo $html;
} else {

    $html = <<<HTML
<!DOCTYPE html>
</style>
<head>
       <title>404</title>
       <link rel="stylesheet" href="//eyer.life/.src/styles/backgroundstyle.css">
       <link rel="icon" type="image/svg+xml" href="//eyer.life/.src/img/favicon.svg">

</head>

<style>
        body {
            text-align: center;
            color: white;
        }
        h1 {
            font-size: 8vh;
        }

        .center-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 90vh;
        }

        img {
            max-width: 80vh;
            height: auto;
        }
    </style>

<body>

<div class="center-container">
<h1>Video not found</h1>
<img src="404.webp" alt="404">
</div>
</body>
</html>
HTML;
    echo $html;
    }