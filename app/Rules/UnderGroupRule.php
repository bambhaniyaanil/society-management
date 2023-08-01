<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\GroupCreations;
use Auth;

class UnderGroupRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $group_creation = GroupCreations::where('name', $value)->where('society_id', '=', Auth::user()->society_id)->count();
        if($group_creation != 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'group name not found';
    }
}
