<?php

namespace ImLiam\UniqueGmailAddress\Tests;

use Illuminate\Support\Facades\DB;
use ImLiam\UniqueGmailAddress\UniqueGmailAddressRule;

class UniqueGmailAddressRuleTest extends TestCase
{
    public UniqueGmailAddressRule $rule;

    public function setUp(): void
    {
        parent::setUp();

        if (DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            DB::connection()->getPdo()->sqliteCreateFunction("REGEXP", 'preg_match', 2);
        }

        $this->rule = new UniqueGmailAddressRule('emails', 'email');
    }

    protected function assertValidationPasses(string $value, string $field = 'email')
    {
        $this->rule->validate($field, $value, fn ($message) => $this->fail($message));
        $this->addToAssertionCount(1);
    }

    protected function assertValidationFails(string $value, string $field = 'email')
    {
        $failed = false;
        $this->rule->validate($field, $value, function () use (&$failed) {
            $failed = true;
        });

        if ($failed) {
            $this->addToAssertionCount(1);
            return;
        }

        $this->fail('Validation passed unexpectedly.');
    }

    /** @test */
    public function an_empty_database_will_have_no_conflicts()
    {
        $this->assertValidationPasses('test@gmail.com');
    }

    /** @test */
    public function an_exact_match_will_fail()
    {
        DB::table('emails')->insert(['email' => 'test@gmail.com']);
        $this->assertValidationFails('test@gmail.com');
    }

    /** @test */
    public function a_googlemail_to_gmail_address_match_will_fail()
    {
        DB::table('emails')->insert(['email' => 'test@googlemail.com']);
        $this->assertValidationFails('test@gmail.com');
    }

    /** @test */
    public function a_gmail_to_googlemail_address_match_will_fail()
    {
        DB::table('emails')->insert(['email' => 'test@gmail.com']);
        $this->assertValidationFails('test@googlemail.com');
    }

    /** @test */
    public function a_matching_address_with_a_dot_in_the_username_will_fail()
    {
        DB::table('emails')->insert(['email' => 'te.st@gmail.com']);
        $this->assertValidationFails('test@gmail.com');

        DB::table('emails')->insert(['email' => 'ex.am.ple@gmail.com']);
        $this->assertValidationFails('example@gmail.com');
    }

    /** @test */
    public function a_matching_address_with_a_plus_after_the_username_will_fail()
    {
        DB::table('emails')->insert(['email' => 'test+foobar@gmail.com']);
        $this->assertValidationFails('test@gmail.com');
    }

    /** @test */
    public function a_matching_username_at_another_domain_will_pass()
    {
        DB::table('emails')->insert(['email' => 'test@example.com']);
        $this->assertValidationPasses('test@gmail.com');
    }

    /** @test */
    public function a_denormalized_gmail_address_will_fail_if_used_in_the_original_query()
    {
        DB::table('emails')->insert(['email' => 'test+123@googlemail.com']);
        $this->assertValidationFails('test@gmail.com');

        DB::table('emails')->insert(['email' => 'example+123@gmail.com']);
        $this->assertValidationFails('ex.am.ple+vegetables@googlemail.com');
    }
}
