<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateOrderStatusRequest extends FormRequest
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
            'status' => ['required', 'string', Rule::enum(OrderStatus::class)],
        ];
    }
}
