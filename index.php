<?php
    ob_start();
    $root = $_SERVER["DOCUMENT_ROOT"];
    $yt = (object) [];
    $response = "";
    require_once($root . "/vendor/autoload.php");
    $yt -> pageBuildDate = date("Ymd", getlastmod());

    require($root . "/tools/Rehike/Request.php");
    require($root . "/tools/Rehike/InnertubeContext.php");
    require($root . "/tools/Rehike/ContextManager.php");
    require($root . "/tools/YukisCoffee/CoffeeRequest/CoffeeRequest.php");

    $twigLoader = new \Twig\Loader\FilesystemLoader($root . "/template");
    $twig = new \Twig\Environment($twigLoader, [ "debug" => true ]);
    require_once($root . "/tools/functions.php");
    $twig -> addGlobal("yt", $yt);
    $twig -> addGlobal("response", $response);

    require($root . "/tools/router.php");

    if (isset($yt -> template)) {
        echo $twig -> render($yt -> template . ".twig");
    } else {
        echo $twig -> render("404.twig");
    }

    $yt -> alerts = [
        (object) [
            "type" => "info",
            "content" => "This version of youtube is going away soon. <a href=\"#\">Switch to the new YouTube</a>"
        ]
    ];

    ob_end_flush();