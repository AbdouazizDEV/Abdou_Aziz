<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ModuleResource;
class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (is_null($this->resource)) {
            return [];
        }
        // //return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'nom' => $this->nom,
        //     'description' => $this->description,
        // ];
        return [
            'id' => $this->id,
            'apprenant_id' => $this->apprenant_id,
            'module_id' => $this->module_id,
            'note' => $this->note,
            'module' => new ModuleResource($this->whenLoaded('module')),  // Charger la relation uniquement si elle est chargée
        ];
    }
}
