<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $message;
    private $hasData;

    public function __construct($resource = null, string $message)
    {
        if ($resource) {
            parent::__construct($resource);
            $this->hasData = true;
        } else {
            $this->hasData = false;
        }

        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        if ($this->hasData) {
            return [
                'data' => [
                    'id' => $this->id,
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password,
                    'number_contact' => $this->number_contact,
                ],
                'message' => $this->message,
            ];
        }

        return [
            'data' => [],
            'message' => $this->message,
        ];
    }
}
