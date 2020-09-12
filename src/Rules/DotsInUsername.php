<?php

namespace ImLiam\UniqueGmailAddress\Rules;

class DotsInUsername implements EmailRule
{
    public function normalize(string $email): string
    {
        [$username, $domain] = explode('@', $email, 2);
        $username = str_replace('.', '', $username);

        return $username . '@' . $domain;
    }

    public function regex(string $email): string
    {
        [$username, $domain] = explode('@', $email, 2);

        $characters = array_map(function ($character) {
            return preg_quote($character);
        }, str_split($username));

        // Each character in the username can have optional dots between them
        return join('(\.?)+', $characters) . '@' . $domain;
    }
}
