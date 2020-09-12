<?php

namespace ImLiam\UniqueGmailAddress;

class Util
{
    public static function isGmailAddress(string $email): bool
    {
        [$localPart, $domain] = explode('@', $email, 2);

        return in_array($domain, ['gmail.com', 'googlemail.com']);
    }

    public static function isGsuiteAddress(string $email): bool
    {
        if (static::isGmailAddress($email)) {
            return false;
        }

        [$localPart, $domain] = explode('@', $email, 2);

        /** @var string[] $mxRecords */
        $successful = getmxrr($domain, $mxRecords);

        if (! $successful) {
            return false;
        }

        return in_array('aspmx.l.google.com', $mxRecords);
    }
}
