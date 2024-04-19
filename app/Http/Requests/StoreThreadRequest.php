<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class StoreThreadRequest extends FormRequest
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
            'title' => 'required|string|max:255',  
            'body' => 'required|string',         
            'tags' => 'sometimes|array',           
            'tags.*' => 'integer|exists:threads_tags,id', 
        ];
    }

    /**
     * Custom response for when validation fails on HTMX requests.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->isHtmx()) {
            $errors = view('components.message', ['message' => $validator->errors()])->render();
            throw new \Illuminate\Validation\ValidationException($validator, response($errors, Response::HTTP_BAD_REQUEST));
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
