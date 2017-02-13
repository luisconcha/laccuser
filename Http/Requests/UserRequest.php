<?php

namespace LaccUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $idUser = ( $this->route('user') ) ? $this->route( 'user' ) : null;

        return [
            'name'     => "required|max:150|unique:users,name,$idUser",
            'email'    => "required|email|unique:users,email,$idUser",
            'num_cpf'  => "required|unique:users,num_cpf,$idUser",
        ];
    }
}
