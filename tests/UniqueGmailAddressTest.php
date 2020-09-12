<?php

namespace ImLiam\UniqueGmailAddress\Tests;

use ImLiam\UniqueGmailAddress\UniqueGmailAddress;

class UniqueGmailAddressTest extends TestCase
{
    /** @test */
    public function only_valid_variations_will_pass()
    {
        $validator = new UniqueGmailAddress('test@gmail.com');

        $this->assertTrue($validator->matches('test@gmail.com'));
        $this->assertTrue($validator->matches('test@googlemail.com'));
        $this->assertTrue($validator->matches('te.st@gmail.com'));
        $this->assertTrue($validator->matches('te.st@googlemail.com'));
        $this->assertTrue($validator->matches('test+yepitsme@gmail.com'));

        $this->assertFalse($validator->matches('testy@gmail.com'));
        $this->assertFalse($validator->matches('nottest@gmail.com'));
        $this->assertFalse($validator->matches('nottest@googlemail.com'));
        $this->assertFalse($validator->matches('nottest+nopenotme@gmail.com'));
        $this->assertFalse($validator->matches('test@notgmail.com'));
    }

    /** @test */
    public function a_denormalized_gmail_address_will_also_match()
    {
        $validator = new UniqueGmailAddress('te.st+123@googlemail.com');

        $this->assertTrue($validator->matches('test@gmail.com'));
        $this->assertTrue($validator->matches('tes.t@gmail.com'));
        $this->assertTrue($validator->matches('test@googlemail.com'));
    }

    /** @test */
    public function a_denormalized_gmail_address_can_be_normalized()
    {
        $validator = new UniqueGmailAddress('te.st+123@googlemail.com');

        $this->assertEquals('test@gmail.com', $validator->normalizeAddress());
    }
}
