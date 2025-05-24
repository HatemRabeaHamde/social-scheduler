<?php

 namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{ 

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'scheduled_time' => 'required|date|after:now',
            'platforms' => 'required|array',
            'platforms.*' => 'exists:platforms,id',
        ];
    }
}
