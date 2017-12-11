<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberAddRequest extends FormRequest
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
        return [
            'name'=>'required|max:100|alpha_spaces',
            'address'=>'required|max:300',
            'age'=>'required|numeric|maxlength:2',
            'photo'=>'bail|image|mimes:jpeg,png,gif|max:10240'
        ];
    }

    public function messages(){
        return [
            'name.required'=>'The Name field is required.',
            'name.max'=>'The name may not be greater than 100 characters.',
            'name.alpha_spaces'=>'The Name can contain only Alphabetic characters.',
            'address.required'=>'The Address field is required.',
            'address.max'=>'The name may not be greater than 300 characters.',
            'age.required'=>'The Age field is required.',
            'age.numeric'=>'The Age must be a number.',
            'age.maxlength'=>'Maximum 2 characters',
            'photo.mimes' => 'Photo only allow JPG, GIF, and PNG filetypes.',
            'photo.image'=>'Uploaded file is not a valid image',
            'photo.size'=>'The photo may not be greater than 10240 kilobytes.'
        ];
    }
    

}
