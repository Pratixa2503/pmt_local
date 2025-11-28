<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenericFileUploadRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
       
       return [
            'bulk_file' => [
                'required',          // or 'nullable' if optional
                'file',
                'max:20480',         // 20 MB (Laravel uses KB)
                'mimes:csv,xls,xlsx' // by extension
                // optional extra check by MIME:
                // 'mimetypes:text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
        ];
    }
    
    public function messages(): array
    {
        return [
            'bulk_file.required' => 'Please choose a file to upload.',
            'bulk_file.mimes'    => 'Only CSV, XLS, or XLSX files are allowed.',
            'bulk_file.max'      => 'The file may not be greater than 20 MB.',
        ];
    }

}
