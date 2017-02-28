<?php
namespace LaccUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaccUser\Repositories\RoleRepository;

class RoleRequest extends FormRequest
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    public function __construct( RoleRepository $repository )
    {
        $this->roleRepository = $repository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $role = $this->roleRepository->findByField( 'name', config( 'laccuser.acl.role_admin' ) )->first();
        
        return $this->route( 'role' ) != $role->id;
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
          'name'        => "required|max:150|unique:roles,name,$idRole",
          'cor'         => "required|unique:roles,cor,$idRole",
          'description' => "max:255",
        ];
    }
}
