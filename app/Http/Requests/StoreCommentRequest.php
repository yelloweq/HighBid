<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StoreCommentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Custom response for when validation fails on HTMX requests.
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->isHtmx()) {
            $errors = view('components.message', ['message' => $validator->errors()])->render();
            throw new ValidationException($validator, response($errors, Response::HTTP_BAD_REQUEST));
        }

        parent::failedValidation($validator);
    }

    /**
     * Determine if the request is coming from HTMX.
     *
     * @return bool
     */
    public function isHtmx()
    {
        return $this->header('HX-Request') === 'true';
    }
}
