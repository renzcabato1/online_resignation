<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;

class ManageUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => (string) $this->created_at,
            'user' => UserResource::collection($this->hrUser),
            'approver' => $this->approver,
            'employee' => $this->hrUser->employee,
            // 'employee' => $this->hr
        ];
    }
}
