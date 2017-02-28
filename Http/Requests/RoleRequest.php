<?php
namespace LaccUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $idRole = ( $this->route( 'role' ) ) ? $this->route( 'role' ) : null;

        return [
          'name' => "required|max:150|unique:roles,name,$idRole",
        ];
    }
}
