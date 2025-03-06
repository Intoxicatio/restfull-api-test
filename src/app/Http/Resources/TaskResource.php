<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'create_date' => $this->create_date,
            'status' => $this->status,
            'priority' => $this->priority,
            'category' => $this->category
        ];
    }
}
