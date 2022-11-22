<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $segment = $this->segment(3);

        if (! in_array($segment, ['token', 'login', 'logout'])) {

            if ($this->isMethod('POST')) {

                return [
                    'role_id.*' => 'exists:roles,id',
                    'name' => 'required|max:50',
                    'email' => 'required|email|unique:users,email,NULL,deleted_at',
                    'password' => 'required|confirmed|min:6'
                ];
            }

            if ($this->isMethod('PUT')) {

                return [
                    'role_id.*' => 'exists:roles,id',
                    'name' => 'required|max:50',
                    'lastname' => 'max:50',
                    'email' => 'email|unique:users,email,NULL,deleted_at',
                ];
            }
        }

        return [
            'firstname'  => 'max:50',
            'lastname'   => 'max:50',
        ];
    }
}
