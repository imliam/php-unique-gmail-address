<?php

namespace ImLiam\UniqueGmailAddress\Rules;

class DotsInLocalPart implements EmailRule
{
    public function normalize(string $email): string
    {
        [$localPart, $domain] = explode('@', $email, 2);
        $localPart = str_replace('.', '', $localPart);

        return $localPart . '@' . $domain;
    }

    public function regex(string $email): string
    {
        [$localPart, $domain] = explode('@', $email, 2);

        $characters = array_map(function ($character) {
            return preg_quote($character);
        }, str_split($localPart));

        // Each character in the localPart can have optional dots between them
        return join('(\.?)+', $characters) . '@' . $domain;
    }
}
