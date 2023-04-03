<?php
if (!isset($_GET["v"])) {
    header("Location: /");
}

$yt -> template = "watch";
$yt -> page = (object) [];
$yt -> videoId = $_GET["v"];

use \Rehike\Request;

Request::innertubeRequest(
    "watch",
    "next",
    (object) [
        "videoId" => $yt -> videoId
    ]
);

$response = Request::getInnertubeResponses()["watch"];
$ytdata = json_decode($response);

$playlistId = "VLUU" . substr($ytdata -> contents -> twoColumnWatchNextResults -> results -> results -> contents[1] -> videoSecondaryInfoRenderer -> owner -> videoOwnerRenderer -> title -> runs[0] -> navigationEndpoint -> browseEndpoint -> browseId, 2);

Request::innertubeRequest(
    "videoCount",
    "browse",
    (object) [
        "browseId" => $playlistId
    ]
);

$vidCntResponse = Request::getInnertubeResponses()["videoCount"];
$vidCntData = json_decode($vidCntResponse);

Request::innertubeRequest(
    "votes",
    "reel/reel_item_watch",
    (object) [
        "disablePlayerResponse" => true,
        "playerRequest" => (object) [
            "videoId" => $yt -> videoId
        ]
    ]
);


$voteResponse = Request::getInnertubeResponses()["votes"];
$votedata = json_decode($voteResponse);

$yt -> response = $response;

$yt -> info = $ytdata -> contents -> twoColumnWatchNextResults -> results -> results -> contents;
$yt -> primaryInfo = $yt -> info[0] -> videoPrimaryInfoRenderer;
$yt -> secondaryInfo = $yt -> info[1] -> videoSecondaryInfoRenderer;

$primaryInfo = (object) [];
$_pi = $primaryInfo;
$_rpi = $yt -> primaryInfo;

$_pi -> title = $_getText($_rpi -> title);
$_pi -> views = isolateViewCount($_getText($_rpi -> viewCount -> videoViewCountRenderer -> viewCount));
$_pi -> likeBtns = $votedata -> overlay -> reelPlayerOverlayRenderer -> likeButton -> likeButtonRenderer;
$_pi -> likes = (int) preg_replace("/( likes)|( like)|(,)/", "", $_pi -> likeBtns -> likeCountWithLikeText -> accessibility -> accessibilityData -> label);
$_pi -> dislikes = (int) preg_replace("/( dislikes)|( dislike)|(,)/", "", $_pi -> likeBtns -> dislikeCountWithDislikeText -> accessibility -> accessibilityData -> label) - 1;
if ($_pi -> likes > 0 and $_pi -> dislikes == 0) {
    $_pi -> sentiment = 100;
} else if ($_pi -> likes + $_pi -> dislikes == 0) {
    $_pi -> sentiment = 50;
} else {
    $_pi -> sentiment = ($_pi -> likes / ($_pi -> likes + $_pi -> dislikes)) * 100;
}

$yt -> page -> primaryInfo = $_pi;

$secondaryInfo = (object) [];
$_si = $secondaryInfo;
$_rsi = $yt -> secondaryInfo;

$_si -> author = (object) [
    "text" => $_getText($_rsi -> owner -> videoOwnerRenderer -> title),
    "url" => $_getUrl($_rsi -> owner -> videoOwnerRenderer),
    "videos" => $vidCntData -> sidebar -> playlistSidebarRenderer -> items[0] -> playlistSidebarPrimaryInfoRenderer -> stats[0] -> runs[0] -> text
];

$yt -> page -> secondaryInfo = $_si;