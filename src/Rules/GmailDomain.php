<?php

namespace ImLiam\UniqueGmailAddress\Rules;

use ImLiam\UniqueGmailAddress\Util;

class GmailDomain implements EmailRule
{
    public function normalize(string $email): string
    {
        if (! Util::isGmailAddress($email)) {
            return $email;
        }

        [$username, $domain] = explode('@', $email, 2);

        return $username . '@gmail.com';
    }

    public function regex(string $email): string
    {
        [$username, $domain] = explode('@', $email, 2);

        return $username . '@(gmail|googlemail).com';
    }
}
