<?php

namespace ImLiam\UniqueGmailAddress;

class UniqueGmailAddress
{
    protected string $originalEmail;
    protected string $email;

    public function __construct(string $email)
    {
        $this->originalEmail = $email;
        $this->email = $this->normalizeAddress();
    }

    public function isGmailAddress()
    {
        return $this->stringEndsWith($this->originalEmail, '@googlemail.com')
            || $this->stringEndsWith($this->originalEmail, '@gmail.com');
    }

    public function normalizeAddress()
    {
        if (! $this->isGmailAddress()) {
            return $this->originalEmail;
        }

        $username = trim(strstr($this->originalEmail, '@', true));
        $username = explode('+', $username)[0];
        $username = str_replace('.', '', $username);

        return "{$username}@gmail.com";
    }

    private function stringEndsWith(string $haystack, string $needle): bool
    {
        if ($needle === '') {
            return true;
        }

        return substr($haystack, -strlen($needle)) === $needle;
    }

    private function getEscapedEmailUsername()
    {
        $emailUsername = strstr($this->email, '@', true);

        $usernameCharacters = array_map(function ($character) {
            return preg_quote($character);
        }, str_split($emailUsername));

        return join('(\.?)+', $usernameCharacters); // Each character in the username can have optional dots between them
    }

    public function getRegexWithDelimiters()
    {
        return '/' . $this->getRegex() . '/';
    }

    public function getRegex()
    {
        if (! $this->isGmailAddress()) {
            return '^' . preg_quote($this->email) . '$'; // Must be an exact match
        }

        return '^' . $this->getEscapedEmailUsername() // Must start with the email username
            . '(\+.*)?' // Can optionally include anything after a literal plus
            . '\@(gmail|googlemail).com$'; // Must end with @gmail.com or @googlemail.com
    }

    public function matches($emailToCompare)
    {
        /** @var array $matches */
        preg_match($this->getRegexWithDelimiters(), $emailToCompare, $matches);

        return $matches !== [];
    }
}
