<?php

namespace AppModules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
    public function rules()
    {
        return [
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id|different:sender_id',
            'amount' => 'required|numeric|min:0.01',
        ];
    }

    public function messages()
    {
        return [
            'receiver_id.different' => 'O recebedor não pode ser o mesmo que o remetente.',
        ];
    }
}
