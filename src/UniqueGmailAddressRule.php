<?php

namespace ImLiam\UniqueGmailAddress;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueGmailAddressRule implements Rule
{
    protected $table;
    protected $column;

    public function __construct($table = 'users', $column = 'email')
    {
        $this->table = $table;
        $this->column = $column;
    }

    public function passes($attribute, $value)
    {
        $validator = new UniqueGmailAddress($value);

        if (! $validator->isGmailAddress()) {
            return true;
        }

        return ! DB::table($this->table)->where($this->column, 'REGEXP', $validator->getRegexWithDelimiters())->exists();
    }

    public function message()
    {
        return 'A variation of the given email address has already been used.';
    }
}
