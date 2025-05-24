<?php

namespace App\DTOs\Post;

class PostData
{
    public function __construct(
        public string $title,
        public string $content,
        public ?string $imageUrl,
        public array $platformIds,
        public \Carbon\Carbon $scheduledTime
    ) {}
}