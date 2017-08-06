<?php

use OceanProject\Models\Mail;
use OceanProject\Models\Userinfo;

$mail = function (int $who, array $message, int $type) {

    $bot  = OceanProject\API\ActionAPI::getBot();

    if (!$bot->minrank($who, 'mail')) {
        return $bot->network->sendMessageAutoDetection($who, $bot->botlang('not.enough.rank'), $type);
    }

    if (!isset($message[1]) || empty($message[1])) {
        return $bot->network->sendMessageAutoDetection(
            $who,
            'Usage: !mail [xatid/regname/read/check/store/unstore/staff/delete] [?info]',
            $type
        );
    }

    switch ($message[1]) {
        case 'read':
            if (!isset($message[2]) || empty($message[2]) || !in_array($message[2], ['old', 'new', 'stored'])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !mail read [old/new/stored]', $type);
            }

            if ($message[2] == 'old') {
                $infos = ['touser' => $who, 'read' => true, 'store' => false];
            } elseif ($message[2] == 'new') {
                $infos = ['touser' => $who, 'read' => false, 'store' => false];
            } else {
                $infos = ['touser' => $who, 'read' => true, 'store' => true];
            }

            $mails = Mail::where($infos)->get();
            if (sizeof($mails) == 0) {
                return $bot->network->sendMessageAutoDetection($who, 'You have no messages!', $type);
            }

            if ($type == 1) {
                $type = 2;
            }

            foreach ($mails as $mail) {
                $user = Userinfo::where('xatid', $mail['fromuser'])->first();
                $bot->network->sendMessageAutoDetection(
                    $who,
                    'Time: ' . gmdate('d/m/Y', $mail->created_at->timestamp) . ' ID: ' . $mail->id . ' From: ' .
                    $user->regname . '(' . $user->xatid . ') Message: ' . $mail->message,
                    $type
                );

                if ($message[2] == 'new') {
                    $mail->read = true;
                    $mail->save();
                }

                usleep(750000);
            }
            return $bot->network->sendMessageAutoDetection($who, 'End of messages.', $type);
            break;

        case 'empty':
            $mails = Mail::where(['touser' => $who, 'store' => false])->get();
            foreach ($mails as $mail) {
                $mail->delete();
            }
            return $bot->network->sendMessageAutoDetection($who, 'Mail inbox emptied!', $type);
            break;

        case 'check':
            $mails['old']    = Mail::where(['touser' => $who, 'read' => true, 'store' => false])->get();
            $mails['new']    = Mail::where(['touser' => $who, 'read' => false, 'store' => false])->get();
            $mails['stored'] = Mail::where(['touser' => $who, 'read' => false, 'store' => true])->get();

            if (sizeof($mails['old']) == 0 && sizeof($mails['new']) == 0 && sizeof($mails['stored']) == 0) {
                return $bot->network->sendMessageAutoDetection($who, 'You have no messages!', $type);
            }

            return $bot->network->sendMessageAutoDetection(
                $who,
                'You have ' . sizeof($mails['new']) . ' unread messages, ' . sizeof($mails['old']) .
                    ' read messages and ' . sizeof($mails['stored']) . ' stored messages!',
                $type
            );
            break;

        case 'delete':
            if (!isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !mail delete [mailID]', $type);
            }

            $id = (int)$message[2];
            $mail = Mail::where(['touser' => $who, 'id' => $id])->first();
            if (!empty($mail)) {
                $mail->delete();
                return $bot->network->sendMessageAutoDetection($who, 'Mail deleted!', $type);
            } else {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'This mail does not exist or does not belong to you!',
                    $type
                );
            }
            break;

        case 'store':
            if (!isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !mail store [mailID]', $type);
            }

            $id = (int)$message[2];
            $mail = Mail::where(['touser' => $who, 'id' => $id])->first();
            if (!empty($mail)) {
                $mail->store = true;
                $mail->save();
                return $bot->network->sendMessageAutoDetection($who, 'Mail stored!', $type);
            } else {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'This mail does not exist or does not belong to you!',
                    $type
                );
            }
            break;

        case 'unstore':
            if (!isset($message[2]) || empty($message[2]) || !is_numeric($message[2])) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !mail unstore [mailID]', $type);
            }

            $id = (int)$message[2];
            $mail = Mail::where(['touser' => $who, 'id' => $id])->first();
            if (!empty($mail)) {
                $mail->store = false;
                $mail->save();
                return $bot->network->sendMessageAutoDetection($who, 'Mail unstored!', $type);
            } else {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'This mail does not exist or does not belong to you!',
                    $type
                );
            }
            break;

        case 'staff':
            unset($message[0]);
            unset($message[1]);
            $message = implode(' ', $message);
            $message = trim($message);

            if (!isset($message) || empty($message)) {
                return $bot->network->sendMessageAutoDetection($who, 'Usage: !mail staff [message]', $type);
            }

            if (!$bot->users[$who]->isMain()) {
                return $bot->network->sendMessageAutoDetection($who, 'Only main owners can use this command!', $type);
            }

            foreach ($bot->stafflist as $id => $level) {
                if ($who != $id) {
                    $mail = new Mail;
                    $mail->touser   = $id;
                    $mail->fromuser = $who;
                    $mail->message  = $message;
                    $mail->save();
                }
            }
            return $bot->network->sendMessageAutoDetection($who, 'Message sent to all staff.', $type);
            break;
        
        default:
            if (!isset($message[1]) || !isset($message[2]) || empty($message[1]) || empty($message[2])) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'Usage: !mail [xatid/regname] [message]',
                    $type
                );
            }

            unset($message[0]);
            $toUser = $message[1];
            unset($message[1]);
            $message = implode(' ', $message);
            $message = trim($message);

            $xatAdmins = [
                7, 'darren',
                100, 'sam',
                101, 'chris',
                804, 'bot',
                42, 'xat',
                225248065, 'tom2',
                69211656, 'tomflash',
                137312609, 'bignum',
                1480868749, 'bignum2'
            ];

            if (in_array(strtolower($toUser), $xatAdmins)) {
                return $bot->network->sendMessageAutoDetection(
                    $who,
                    'You are not allowed to send a mail to this account.',
                    $type
                );
            }

            if (is_numeric($toUser)) {
                $toUser = (int)$toUser;
                $user = Userinfo::where('xatid', $toUser)->first();
            } else {
                $user = Userinfo::where('regname', $toUser)->first();
            }

            if (sizeof($user) > 0) {
                if ($who != $user->xatid) {
                    $mails = Mail::where(['touser' => $user->xatid, 'read' => false])->get()->toArray();

                    if (sizeof($mails) > 10) {
                        return $bot->network->sendMessageAutoDetection(
                            $who,
                            'Sorry, ' . $user->regname . ' has too many unread messages.',
                            $type
                        );
                    }

                    $mail = new Mail;
                    $mail->touser   = $user->xatid;
                    $mail->fromuser = $who;
                    $mail->message  = $message;
                    $mail->save();
                    return $bot->network->sendMessageAutoDetection(
                        $who,
                        'Message sent to ' . $user->regname . '(' . $user->xatid . ')!',
                        $type
                    );
                } else {
                    return $bot->network->sendMessageAutoDetection($who, 'You cannot send a mail to yourself!', $type);
                }
            } else {
                return $bot->network->sendMessageAutoDetection($who, 'This user is not in the database.', $type);
            }
            break;
    }
};
