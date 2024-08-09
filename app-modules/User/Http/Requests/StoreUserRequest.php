<?php

namespace AppModules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'cpf' => 'nullable|required_without:cnpj|string|size:11|unique:users,cpf',
            'cnpj' => 'nullable|required_without:cpf|string|size:14|unique:users,cnpj',
        ];
    }

    public function messages()
    {
        return [
            'cpf.required_without' => 'O CPF é obrigatório se o CNPJ não for fornecido.',
            'cnpj.required_without' => 'O CNPJ é obrigatório se o CPF não for fornecido.',
            'cpf.size' => 'O CPF deve ter exatamente 11 caracteres.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'cnpj.size' => 'O CNPJ deve ter exatamente 14 caracteres.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado.',
        ];
    }
}
