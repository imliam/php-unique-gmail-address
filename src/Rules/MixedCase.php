<?php

namespace ImLiam\UniqueGmailAddress\Rules;

class MixedCase implements EmailRule
{
    public function normalize(string $email): string
    {
        return strtolower($email);
    }

    public function regex(string $email): string
    {
        [$localPart, $domain] = explode('@', $email, 2);

        return '(?i)' . $localPart . '(?-i)' . '@' . $domain;
    }
}
