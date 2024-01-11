<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeniusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'title' => $this['result']['full_title'],
            'artist' => $this['result']['artist_names'],
            'api_path' => 'https://api.genius.com'.$this['result']['api_path'],
            'song_id' => $this['result']['id'],
            'slug' => $this['result']['path'],
        ];
    }
}
