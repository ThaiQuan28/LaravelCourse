<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostContact extends FormRequest
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
            'id' => 'sometimes|integer|exists:contacts,id',
            'name' => 'required|string|max:255',
             'email' => [
            'required',
            'email', 
            'unique:contacts,email' . ($this->id ? ',' . $this->id : ''),
          
        ],
            'phone' => [
            'nullable',
            'string',
            'max:20',
            'regex:/^\d{10}$/', 
        ],
            'address' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã bị trùng, vui lòng nhập email khác.',
            'phone.required' => 'Số điện thoại không được để trống.',
             'phone.regex' => 'Số điện thoại phải đủ 10 chữ số.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'errors' => $validator->errors()
        ], 422));
    }
}
