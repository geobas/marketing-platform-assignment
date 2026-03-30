<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotExampleDomain implements ValidationRule
{
    /**
     * Validate the given attribute value.
     *
     * This method checks if the email domain is an "example" domain (e.g., example.com, example.org)
     * and fails the validation if so.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Extract domain
        $domain = substr(strrchr((string) $value, '@'), 1); // @phpstan-ignore-line

        // Block any example.* domain
        if (preg_match('/^example\./i', $domain)) {
            $fail('Emails using example domains are not allowed.');
        }
    }
}
