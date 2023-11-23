<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'required',
            'status_id' => 'required',
            'publication_files_doc' => 'nullable|array|max:5',
            'publication_files_doc.*' => 'mimes:pdf,doc,xls',
        ];
    }

}
