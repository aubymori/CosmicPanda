<?php
$yt -> template = "home";
$yt -> page = (object) [];

use \Rehike\Request;

Request::innertubeRequest(
    "feed",
    "browse",
    (object) [
        "browseId" => "FEwhat_to_watch"
    ]
);

$response = Request::getInnertubeResponses()["feed"];
$ytdata = json_decode($response);

$yt -> page -> videoList = $ytdata -> contents -> twoColumnBrowseResultsRenderer -> tabs[0] -> tabRenderer -> content -> richGridRenderer -> contents;