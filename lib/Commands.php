<?php

namespace Ocean\Xat;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Commands implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container["commands"] = function () use ($container) {
            $commands = new Container();

            $commands["8ball"] = function () use ($container) {
                return new Commands\Ball();
            };
            $commands["active"] = function () use ($container) {
                return new Commands\Active();
            };
            $commands["badge"] = function () use ($container) {
                return new Commands\Badge();
            };
            $commands["ban"] = function () use ($container) {
                return new Commands\Ban();
            };
            $commands["boot"] = function () use ($container) {
                return new Commands\Boot();
            };
            $commands["bump"] = function () use ($container) {
                return new Commands\Bump();
            };
            $commands["calc"] = function () use ($container) {
                return new Commands\Calc();
            };
            $commands["choose"] = function () use ($container) {
                return new Commands\Choose();
            };
            $commands["dev"] = function () use ($container) {
                return new Commands\Dev();
            };
            $commands["dice"] = function () use ($container) {
                return new Commands\Dice();
            };
            $commands["die"] = function () use ($container) {
                return new Commands\ToDie();
            };
            $commands["dunce"] = function () use ($container) {
                return new Commands\Dunce();
            };
            $commands["dx"] = function () use ($container) {
                return new Commands\Dx();
            };
            $commands["gag"] = function () use ($container) {
                return new Commands\Gag();
            };
            $commands["gamebanme"] = function () use ($container) {
                return new Commands\GameBanMe();
            };
            $commands["gameban"] = function () use ($container) {
                return new Commands\GameBan();
            };
            $commands["guestself"] = function () use ($container) {
                return new Commands\GuestSelf();
            };
            $commands["hush"] = function () use ($container) {
                return new Commands\Hush();
            };
            $commands["joke"] = function () use ($container) {
                return new Commands\Joke();
            };
            $commands["kick"] = function () use ($container) {
                return new Commands\Kick();
            };
            $commands["latestpower"] = function () use ($container) {
                return new Commands\LatestPower();
            };
            $commands["listsmilies"] = function () use ($container) {
                return new Commands\ListSmilies();
            };
            $commands["misc"] = function () use ($container) {
                return new Commands\Misc();
            };
            $commands["mostactive"] = function () use ($container) {
                return new Commands\MostActive();
            };
            $commands["mute"] = function () use ($container) {
                return new Commands\Mute();
            };
            $commands["naughtystep"] = function () use ($container) {
                return new Commands\NaughtyStep();
            };
            $commands["online"] = function () use ($container) {
                return new Commands\Online();
            };
            $commands["poorest"] = function () use ($container) {
                return new Commands\Poorest();
            };
            $commands["price"] = function () use ($container) {
                return new Commands\Price();
            };
            $commands["randomsmiley"] = function () use ($container) {
                return new Commands\RandomSmiley();
            };
            $commands["randomuser"] = function () use ($container) {
                return new Commands\RandomUser();
            };
            $commands["rankme"] = function () use ($container) {
                return new Commands\Rankme();
            };
            $commands["reverseban"] = function () use ($container) {
                return new Commands\ReverseBan();
            };
            $commands["say"] = function () use ($container) {
                return new Commands\Say();
            };
            $commands["shortname"] = function () use ($container) {
                return new Commands\Shortname();
            };
            $commands["sinbin"] = function () use ($container) {
                return new Commands\Sinbin();
            };
            $commands["slots"] = function () use ($container) {
                return new Commands\Slots();
            };
            $commands["started"] = function () use ($container) {
                return new Commands\Started();
            };
            $commands["store"] = function () use ($container) {
                return new Commands\Store();
            };
            $commands["temp"] = function () use ($container) {
                return new Commands\Temp();
            };
            $commands["twitch"] = function () use ($container) {
                return new Commands\Twitch();
            };
            $commands["unbadge"] = function () use ($container) {
                return new Commands\UnBadge();
            };
            $commands["unban"] = function () use ($container) {
                return new Commands\UnBan();
            };
            $commands["undunce"] = function () use ($container) {
                return new Commands\UnDunce();
            };
            $commands["unnaughtystep"] = function () use ($container) {
                return new Commands\UnNaughtyStep();
            };
            $commands["unyellowcard"] = function () use ($container) {
                return new Commands\UnYellowCard();
            };
            $commands["users"] = function () use ($container) {
                return new Commands\Users();
            };
            $commands["value"] = function () use ($container) {
                return new Commands\Value();
            };
            $commands["whatis"] = function () use ($container) {
                return new Commands\Whatis();
            };
            $commands["wikipedia"] = function () use ($container) {
                return new Commands\Wikipedia();
            };
            $commands["xd"] = function () use ($container) {
                return new Commands\Xd();
            };
            $commands["yellowcard"] = function () use ($container) {
                return new Commands\YellowCard();
            };
            $commands["youtube"] = function () use ($container) {
                return new Commands\Youtube();
            };
            $commands["zap"] = function () use ($container) {
                return new Commands\Zap();
            };
            $commands["zipban"] = function () use ($container) {
                return new Commands\Zipban();
            };

            return $commands;
        };
    }
}
