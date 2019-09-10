<?php

namespace App\Http\Requests;

use App\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Autoritzem nomes si es un professor
        return auth()->user()->role_id === Role::TEACHER;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            // Si es get o delete retornem un array buit
	        case 'GET':
	        case 'DELETE':
                return [];
            // Si es post retornem l'array amb les regles
	        case 'POST': {
	        	return [
	        	    'name' => 'required|min:5',
			        'description' => 'required|min:30',
			        'level_id' => [
                        'required',
                        // el level id existeix a la taula level
                        Rule::exists('levels', 'id')
			        ],
		            'categoory_id' => [
			            'required',
			            Rule::exists('categoories', 'id')
		            ],
                    'picture' => 'required|image|mimes:jpg,jpeg,png',
                    //requeriment 1 es requerit si hem omplert requeriment 2
			        'requirements.0' => 'required_with:requirements.1',
			        'goals.0' => 'required_with:goals.1',
		        ];
	        }
	        case 'PUT': {
		        return [
			        'name' => 'required|min:5',
			        'description' => 'required|min:30',
			        'level_id' => [
				        'required',
				        Rule::exists('levels', 'id')
			        ],
			        'categoory_id' => [
				        'required',
				        Rule::exists('categoories', 'id')
			        ],
			        'picture' => 'sometimes|image|mimes:jpg,jpeg,png',
			        'requirements.0' => 'required_with:requirements.1',
			        'goals.0' => 'required_with:goals.1',
		        ];
	        }
        }
    }
}
