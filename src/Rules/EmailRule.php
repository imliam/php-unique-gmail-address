<?php

namespace ImLiam\UniqueGmailAddress\Rules;

interface EmailRule
{
    public function normalize(string $email): string;

    public function regex(string $email): string;
}
