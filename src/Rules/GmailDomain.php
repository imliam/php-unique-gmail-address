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

        [$localPart, $domain] = explode('@', $email, 2);

        return $localPart . '@gmail.com';
    }

    public function regex(string $email): string
    {
        [$localPart, $domain] = explode('@', $email, 2);

        return $localPart . '@(gmail|googlemail).com';
    }
}
