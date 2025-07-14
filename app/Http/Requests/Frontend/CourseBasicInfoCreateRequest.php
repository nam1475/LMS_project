<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class CourseBasicInfoCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'message_for_commit' => ['required', 'max:255', 'string'],
            'title' => ['required', 'max:255', 'string'],
            'seo_description' => ['nullable', 'max:255', 'string'],
            'thumbnail' => ['required', 'image', 'max:3000'],
            'demo_video_storage' => ['nullable', 'in:youtube,vimeo,external_link,upload', 'string', 'size:255', 'mimes:mp4, mov'],
            'price' => ['required', 'numeric'],
            'discount' => ['nullable', 'numeric'],
            'description' => ['required'],
            'demo_video_source' => ['nullable'],
            // 'capacity' => ['required', 'numeric'],
            // 'qna' => ['nullable', 'boolean'],
            'certificate' => ['nullable', 'boolean'],
            'category' => ['required', 'integer'],
            'level' => ['required', 'integer'],
            'language' => ['required', 'integer'],
        ];
    }
}
