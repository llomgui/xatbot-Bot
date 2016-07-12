<?php

namespace Ocean\Xat;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Modules implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container["modules"] = function () use ($container) {
            $modules = new Container();

            $modules["onApp"] = function () use ($container) {
                return new Modules\OnApp();
            };
            $modules["onChatInfo"] = function () use ($container) {
                return new Modules\OnChatInfo();
            };
            $modules["onControlMessage"] = function () use ($container) {
                return new Modules\OnControlMessage();
            };
            $modules["onDone"] = function () use ($container) {
                return new Modules\OnDone();
            };
            $modules["onDup"] = function () use ($container) {
                return new Modules\OnDup();
            };
            $modules["onFriendList"] = function () use ($container) {
                return new Modules\OnFriendList();
            };
            $modules["onGroupPowers"] = function () use ($container) {
                return new Modules\OnGroupPowers();
            };
            $modules["onIdle"] = function () use ($container) {
                return new Modules\OnIdle();
            };
            $modules["onMessage"] = function () use ($container) {
                return new Modules\OnMessage();
            };
            $modules["onOldMessage"] = function () use ($container) {
                return new Modules\OnOldMessage();
            };
            $modules["onPC"] = function () use ($container) {
                return new Modules\OnPC();
            };
            $modules["onPM"] = function () use ($container) {
                return new Modules\OnPM();
            };
            $modules["onTickle"] = function () use ($container) {
                return new Modules\OnTickle();
            };
            $modules["onTransfer"] = function () use ($container) {
                return new Modules\OnTransfer();
            };
            $modules["onUserJoined"] = function () use ($container) {
                return new Modules\OnUserJoined();
            };
            $modules["onUserLeave"] = function () use ($container) {
                return new Modules\OnUserLeave();
            };

            return $modules;
        };
    }
}
