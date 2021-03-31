<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Route;

class UserCrudRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->route('user');
        // dd($id);
        return [
            'firstname' => 'required|min:3|max:191',
            'lastname' => 'required|min:3|max:191',
            "email" => "required|email|unique:users,email,".$id,
            'phone' => 'min:10',
            'address' => 'min:3',
            'client_id' => 'numeric',
            'city_id' => 'numeric',
            'status' => 'min:3|max:191',
        ];
    }

}