<?php

$twig -> addFunction(new \Twig\TwigFunction('getText', function ($obj) {
    if (isset($obj->runs)) {
       //return '';
       $runs = $obj->runs;
       $response = '';
       for ($i = 0, $j = count($runs); $i < $j; $i++) {
          $response .= $runs[$i]->text;
       }
       return $response;
    } else if (isset($obj->simpleText)) {
       return $obj->simpleText;
    } else {
       return '';
    }
 }));

 $_getText = function ($obj) {
    if (isset($obj->runs)) {
       //return '';
       $runs = $obj->runs;
       $response = '';
       for ($i = 0, $j = count($runs); $i < $j; $i++) {
          $response .= $runs[$i]->text;
       }
       return $response;
    } else if (isset($obj->simpleText)) {
       return $obj->simpleText;
    } else {
       return '';
    }
 };

 $twig -> addFunction(new \Twig\TwigFunction('getThumb', function($obj, $height = 110) {
    if (isset($obj -> thumbnail)) {
        $thumbs = $obj -> thumbnail -> thumbnails;
    } else if (isset($obj -> thumbnails)) {
        $thumbs = $obj -> thumbnails[0] -> thumbnails;
    } else {
        return;
    }

    for ($i = 0; $i < count($thumbs); $i++) {
        if ($thumbs[$i] -> height >= $height) {
            return $thumbs[$i] -> url;
        }
    }
}));

$_getThumb = function($obj, $height = 110) {
    if (isset($obj -> thumbnail)) {
        $thumbs = $obj -> thumbnail -> thumbnails;
    } else if (isset($obj -> thumbnails)) {
        $thumbs = $obj -> thumbnails[0] -> thumbnails;
    } else {
        return;
    }

    for ($i = 0; $i < count($thumbs); $i++) {
        if ($thumbs[$i] -> height >= $height) {
            return $thumbs[$i] -> url;
        }
    }
};

$twig -> addFunction(new \Twig\TwigFunction('getUrl', function($obj) {
    if (isset($obj->navigationEndpoint->commandMetadata->webCommandMetadata->url)) {
        return $obj->navigationEndpoint->commandMetadata->webCommandMetadata->url;
    } else {
        return '';
    }
}));

$_getUrl = function($obj) {
    if (isset($obj->navigationEndpoint->commandMetadata->webCommandMetadata->url)) {
        return $obj->navigationEndpoint->commandMetadata->webCommandMetadata->url;
    } else {
        return '';
    }
};

function isolateViewCount($count) {
    $count = preg_replace("/( views)|( view)/", "", $count);
    if ($count == "No") {
        return "0";
    } else {
        return $count;
    }
}