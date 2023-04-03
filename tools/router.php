<?php
function betterParseUrl($url) {
    $purl = parse_url($url);
    $response = (object) [];
    foreach(explode(" ", "scheme host port user pass fragment") as $elm) {
        if (isset($purl[$elm])) {
            $response -> {$elm} = $purl[$elm];
        }
    }

    if (isset($purl["path"])) {
        $temp = explode("/", $purl["path"]);
        if ($temp[0] === "") {
            array_splice($temp, 0, 1);
        }
        $response -> path = $temp;
    }

    if (isset($purl["query"])) {
        $response -> query = explode("&", $purl["query"]);
    }

    return $response;
}

$routerUrl = betterParseUrl($_SERVER["REQUEST_URI"]);

switch($routerUrl -> path[0]) {
    case "yts":
    case "s":
    case "youtubei":
        require($root . "/simplefunnel.php");
        die();
        break;
    case "":
        include($root . "/view/home.php");
        break;
    case "watch":
        include($root . "/view/watch.php");
        break;
    default:
        echo $twig -> render("404.twig");
        die();
        break;
}