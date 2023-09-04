<?php

namespace App\Http\Resources;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabelResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => new UserResources($this->user),
            //'blogs' => BlogResources::collection($this->blogs), //bloglar birden fazla olduğu için collection kullanıoruz.
        ];
    }
}
