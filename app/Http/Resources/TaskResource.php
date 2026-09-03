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
            // 'id' => $this->id,
            // 'user_id' => $this->user_id,
            'title' => $this->title,
            'description' => $this->description,

            'user' => new UserResource($this->whenLoaded('user')),
            // 'files' => FileResource::collection($this->whenLoaded('files')),

            'due_at' => $this->due_at?->toIso8601String(),
            'priority' => $this->priority->value,
            'status' => $this->status->value,

            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
