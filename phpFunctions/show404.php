<?php
/**
 * @return void
 */
function show404 (): void
{
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