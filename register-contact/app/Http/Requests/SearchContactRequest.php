<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchContactRequest extends FormRequest
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
            'q' => 'required|string|min:1|max:255',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'paginated' => 'sometimes|string|in:true,false',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'q.required' => 'Từ khóa tìm kiếm không được để trống.',
            'q.string' => 'Từ khóa tìm kiếm phải là chuỗi ký tự.',
            'q.min' => 'Từ khóa tìm kiếm phải có ít nhất 1 ký tự.',
            'q.max' => 'Từ khóa tìm kiếm không được vượt quá 255 ký tự.',
            'per_page.integer' => 'Số lượng per page phải là số nguyên.',
            'per_page.min' => 'Số lượng per page phải lớn hơn 0.',
            'per_page.max' => 'Số lượng per page không được vượt quá 100.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
