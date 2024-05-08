<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $Data = [
            'name' => $this->name,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'status' => $this->status,
            'classRoom' => $this->classRoom,
            'payment_status' => $this->payment_status,
            'payment_date' => $this->payment_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'google_id' => $this->google_id,
            'tasks'  => TaskResource::collection($this->whenLoaded('tasks')),
        ];


        return $Data;
    }
}
