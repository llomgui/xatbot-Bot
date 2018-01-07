<?php

use Abraham\TwitterOAuth\TwitterOAuth;

$twitter = function (int $who, array $message, int $type) {

    $bot  = xatbot\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'twitter')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !twitter [search]',
            $type
        );
    }

    $consumerKey = xatbot\Bot\XatVariables::getAPIKeys()['twitter']['consumer_key'];
    $consumerSecret = xatbot\Bot\XatVariables::getAPIKeys()['twitter']['consumer_secret'];
    $accessToken = xatbot\Bot\XatVariables::getAPIKeys()['twitter']['oauth_token'];
    $accessTokenSecret = xatbot\Bot\XatVariables::getAPIKeys()['twitter']['oauth_token_secret'];

    if (empty($consumerKey) || empty($consumerSecret) || empty($accessToken) || empty($accessTokenSecret)) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Twitter API Key needs to be setup',
            $type
        );
    }

    $twitter = new TwitterOAuth(
        $consumerKey,
        $consumerSecret,
        $accessToken,
        $accessTokenSecret
    );

    $UserTweets = $twitter->get('statuses/user_timeline', [
        'screen_name' => $message[1],
        'count' => 1
    ]);

    $TweetInfos = [];

    foreach ($UserTweets as $tweet) {
        if (!is_object($tweet)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->botlang('cmd.twitter.notfound'),
                $type
            );
        }
        $TweetInfos = [
            'name' => $tweet->user->name,
            'followCount' => number_format($tweet->user->followers_count),
            'text' => str_replace('#', '_#', $tweet->text)
        ];
    }

    if (empty($TweetInfos)) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            $bot->botlang('cmd.twitter.notfound'),
            $type
        );
    }

    $bot->network->sendMessageAutoDetection(
        $who,
        $bot->botlang('cmd.twitter.message', [
            $TweetInfos['name'],
            $TweetInfos['followCount'],
            $TweetInfos['text']
        ]),
        $type
    );
};
