<?php

namespace App\Http\Requests;

use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use Illuminate\Contracts\Validation\ValidationRule;
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

    public function rules(): array
    {
        //TODO: File max size here might differ from what is allowed in dropzone
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'features' => 'required|string',
            'file' => 'mimes:png,jpg,jpeg,gif|max:5000',
            'auction-type' => [new Enum(AuctionType::class)],
            'delivery-type' => [new Enum(DeliveryType::class)],
            'end-time' => 'required|date|after:' . Carbon::now(),
        ];
    }

    protected function failedValidation(Validator $validator): Response
    {
        //TODO: passing Types like that seems redundant, find better way of propagating them.
        return response()->view('components.auction-create-form', [
            'auctionTypes' => AuctionType::cases(),
            'deliveryTypes' => DeliveryType::cases(),
            'errors' => $validator->errors(),
        ], 422);
    }
}
