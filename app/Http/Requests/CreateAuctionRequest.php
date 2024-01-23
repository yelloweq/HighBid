<?php

namespace App\Http\Requests;

use App\Enums\AuctionType;
use App\Enums\DeliveryType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateAuctionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'auction-type' => ['required', Rule::in(AuctionType::cases())],
            'delivery-type' => ['required', Rule::in(DeliveryType::cases())],
            'start-time' => 'required|date',
            'end-time' => 'required|date|after:start-time',
        ];
    }
}

