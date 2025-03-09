<?php

namespace ImLiam\UniqueGmailAddress;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;
use Override;

class UniqueGmailAddressRule implements ValidationRule
{
    public function __construct(
        protected string $table = 'users',
        protected string $column = 'email',
    ) {}

    #[Override]
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = new UniqueGmailAddress($value);

        if (! $validator->isGmailAddress()) {
            return;
        }

        $alreadyExists = !DB::table($this->table)->where($this->column, 'REGEXP', $validator->getRegexWithDelimiters())->exists();

        if ($alreadyExists) {
            $fail('A variation of the given email address has already been used.');
        }
    }
}
