<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ServiceType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreLaundryOrderRequest extends FormRequest
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
     * @return array<string, array<int, ValidationRule|\Illuminate\Contracts\Validation\Rule|Enum|string>>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required_without:phone', 'nullable', 'string', 'max:30'],
            'phone' => ['required_without:customer_phone', 'nullable', 'string', 'max:30'],
            'weight_kg' => ['required', 'numeric', 'min:2'],
            'service_type' => ['required', 'string', Rule::enum(ServiceType::class)],
        ];
    }

    /**
     * Get the sanitized customer phone number.
     */
    public function getCustomerPhone(): string
    {
        return (string) ($this->input('customer_phone') ?? $this->input('phone'));
    }
}
