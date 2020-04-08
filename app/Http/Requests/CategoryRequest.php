<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        return false;
        return true; //после заполнения rules
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       $rules = [
           'code' =>'required|min:3|max:255|unique:categories,code', //    required - означает обязательно к заполнению
           'name' => 'required|min:3|max:255',
            'description' => 'required|min:5', //после созданию класса CategoryRequest,  переносим правила туда

        ];
        //$this->route()->named('categiries.store'); //передаем название маршрута
       //dd(get_class_methods($this->route()));
        if ($this->route()->named('categories.update')){
            $rules['code'] .=','.$this->route()->parameter('category')->id;
        }

            return $rules;
    }

    public function messages()
    {
        return [
            'required' => 'Полу :attribute обязательно к заполнению',
            'min' => 'Поле :attribute дожно иметь минимум :min символов',
            'code.min' => 'Полу код дожно содержать не менее :min символов',
        ];
    }
}
