<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class MissionCrudRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest {

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
        return [
            'title' => 'required|min:3|max:191',
            'description' => 'required',
            'duration' => 'required|numeric',
            'tjm' => 'required|numeric',
            'status' => 'required|min:3|max:191',
            'city_id' => 'required|numeric',
            'client_id' => 'required|numeric',
        ];
    }

}