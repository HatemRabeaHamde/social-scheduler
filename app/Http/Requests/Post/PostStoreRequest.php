<?php

 
namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\DTOs\Post\PostData;
use Carbon\Carbon;

class PostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;  
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'platforms' => 'required|array',
            'platforms.*' => 'exists:platforms,id',
            'scheduled_time' => 'required|date|after:now',   
        ];
    }

    public function toDTO(): PostData
    {
         $scheduled = Carbon::parse($this->input('scheduled_time'));

        if ($scheduled->lessThanOrEqualTo(now())) {
            throw new \InvalidArgumentException('Scheduled time must be in the future.');
        }

        $imageUrl = null;
        if ($this->hasFile('image')) {
            $image = $this->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/posts'), $filename);
            $imageUrl = 'images/posts/' . $filename;
        }

        return new PostData(
            title: $this->input('title'),
            content: $this->input('content'),
            imageUrl: $imageUrl,
            platformIds: $this->input('platforms'),
            scheduledTime: $scheduled,
        );
    }
}
