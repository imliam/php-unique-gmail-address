<?php

namespace ImLiam\UniqueGmailAddress;

use ImLiam\UniqueGmailAddress\Rules\DotsInUsername;
use ImLiam\UniqueGmailAddress\Rules\GmailDomain;
use ImLiam\UniqueGmailAddress\Rules\MixedCase;
use ImLiam\UniqueGmailAddress\Rules\TagInUsername;

class UniqueGmailAddress extends UniqueEmailAddress
{
    public function __construct(string $email)
    {
        parent::__construct($email);

        if (Util::isGmailAddress($this->email) || Util::isGsuiteAddress($this->email)) {
            $this->rules[GmailDomain::class] = new GmailDomain();
            // $this->rules[MixedCase::class] = new MixedCase();
            $this->rules[DotsInUsername::class] = new DotsInUsername();
            $this->rules[TagInUsername::class] = new TagInUsername('+');
        }
    }
}
