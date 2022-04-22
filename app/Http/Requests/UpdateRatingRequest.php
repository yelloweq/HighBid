<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UpdateRatingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'value' => 'required|integer|between:-1,1'
        ];
    }


    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        $this->redirector()->setIntendedUrl(route('login'));

        throw new HttpResponseException(
            redirect()->guest(route('login'))->withErrors('Authorization failed. Please login to continue.')
        );
    }
}
