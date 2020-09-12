<?php

namespace ImLiam\UniqueGmailAddress;

use ImLiam\UniqueGmailAddress\Rules\DotsInLocalPart;
use ImLiam\UniqueGmailAddress\Rules\GmailDomain;
use ImLiam\UniqueGmailAddress\Rules\MixedCase;
use ImLiam\UniqueGmailAddress\Rules\TagInLocalPart;

class UniqueGmailAddress extends UniqueEmailAddress
{
    public function __construct(string $email)
    {
        parent::__construct($email);

        if (Util::isGmailAddress($this->email) || Util::isGsuiteAddress($this->email)) {
            $this->rules[GmailDomain::class] = new GmailDomain();
            // $this->rules[MixedCase::class] = new MixedCase();
            $this->rules[DotsInLocalPart::class] = new DotsInLocalPart();
            $this->rules[TagInLocalPart::class] = new TagInLocalPart('+');
        }
    }
}
