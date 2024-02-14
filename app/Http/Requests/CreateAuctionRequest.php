<?php

namespace App\Http\Requests;

use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class CreateAuctionRequest extends FormRequest
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
            'description' => 'required|string',
            'price' => 'required|numeric',
            'features' => 'required|string',
            'file' => 'mimes:png,jpg,jpeg,gif|max:5000',
            'auction-type' => [new Enum(AuctionType::class)],
            'delivery-type' => [new Enum(DeliveryType::class)],
            'end-time' => 'required|date|after:'. Carbon::now(),
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        if (!$this->header('HX-Request')) {
            throw new HttpResponseException(
                response()->json(['errors' => $validator->errors()], 422)
            );
        }
        
        return response()->view('components.auction-create-form', [
            'auctionTypes' => AuctionType::cases(),
            'deliveryTypes' => DeliveryType::cases(),
            'errors' => $validator->errors(),
        ], 422);
    }

}
