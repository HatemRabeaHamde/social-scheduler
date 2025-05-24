<?php
 namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateStatusRequest extends FormRequest
{
    

    public function rules()
    {
        return [
            'status' => 'required|in:draft,scheduled,published',
            'scheduled_time' => 'required_if:status,scheduled|nullable|date|after:now',
        ];
    }
}
