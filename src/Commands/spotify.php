<?php

use OceanProject\API\ActionAPI;
use OceanProject\API\DataAPI;
use OceanProject\Bot\XatVariables;
use Illuminate\Database\Capsule\Manager as Capsule;

$spotify = function (int $who, array $message, int $type) {

    $bot = ActionAPI::getBot();

    if (!$bot->minrank($who, 'spotify')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!DataAPI::isSetVariable('spotify_' . $who)) {
        return $bot->network->sendMessageAutoDetection($who, 'Please refresh!', $type);
    }

    $spotify = DataAPI::get('spotify_' . $who);

    if (!empty($spotify['accessToken'])) {
        $api = new \SpotifyWebAPI\SpotifyWebAPI();

        try {
            $api->setAccessToken($spotify['accessToken']);
            $currentTrack = $api->getMyCurrentTrack();
        } catch (\SpotifyWebAPI\SpotifyWebAPIException $e) {
            if ($e->getMessage() == 'The access token expired') {
                if (empty($spotify['refreshToken'])) {
                    return $bot->network->sendMessageAutoDetection($who, 'Please relogin to Spotify on panel.', $type);
                }

                $client_id = XatVariables::getAPIKeys()['spotify']['client_id'];
                $secret_id = XatVariables::getAPIKeys()['spotify']['secret_id'];
                $redirect_uri = XatVariables::getAPIKeys()['spotify']['redirect_uri'];

                $session = new \SpotifyWebAPI\Session($client_id, $secret_id, $redirect_uri);
                $session->refreshAccessToken($spotify['refreshToken']);

                $spotify['accessToken'] = $session->getAccessToken();
                $spotify['refreshToken'] = $session->getRefreshToken();

                Capsule::table('users')->where('xatid', $who)->update(['spotify' => json_encode($spotify)]);

                $api->setAccessToken($spotify['accessToken']);
                $currentTrack = $api->getMyCurrentTrack();
            } else {
                var_dump($e->getMessage());
                return $bot->network->sendMessageAutoDetection($who, $e->getMessage(), $type);
            }
        }

        if (is_null($currentTrack)) {
            return $bot->network->sendMessageAutoDetection(
                $who,
                $bot->users[$who]->getRegname() . ' is not listening to Spotify.',
                $type
            );
        }

        $artistsArray = [];
        $artists = $currentTrack->item->artists;
        for ($i = 0; $i < sizeof($artists); $i++) {
            $artistsArray[] = $artists[$i]->name;
        }

        $artist = substr(implode(', ', $artistsArray), 0, -2);

        $song = $currentTrack->item->name;

        if ($currentTrack->is_playing) {
            $string = $bot->users[$who]->getRegname() . ' is listening to: ';
        } else {
            $string = $bot->users[$who]->getRegname() . ' was listening to: ';
        }

        return $bot->network->sendMessageAutoDetection($who, $string . $artist . ' - ' . $song, $type);
    }

    return $bot->network->sendMessageAutoDetection($who, 'You don\'t have spotify linked to your account.', $type);
};
