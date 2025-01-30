<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaskRequest extends FormRequest
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
            'title' => 'required|max:50',
            'description' => 'required|max:255',
            'state' => 'required|in:1,2,3',
            'Priority' => 'required|in:1,2,3',
            'Due_Date' => 'required|date',
            'tenant_id' => 'nullable|exists:tenants,id',
        ];
    }

    
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors occurred',
            'errors' => $errors
        ], 400));
    }
}
