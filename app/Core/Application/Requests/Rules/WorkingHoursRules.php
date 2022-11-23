<?php

namespace App\Core\Application\Requests\Rules;

use App\Core\Application\Requests\Rules\Exceptions\ValidationRuleException;
use Illuminate\Contracts\Validation\Rule;

class WorkingHoursRules implements Rule {
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws ValidationRuleException
     */
    public function passes($attribute, $value): bool {
        $this->validateTime($value);

        return $value > '00:00' && $value < '23:55';
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string {
        return 'Not valid working hour';
    }

    /**
     * @param string $value
     * @return void
     * @throws ValidationRuleException
     */
    private function validateTime(string $value): void {
        if(!preg_match('/\d{2}:\d{2}/', $value)) {
             throw new ValidationRuleException('Time does not match to pattern HH:MM');
        }
    }
}
