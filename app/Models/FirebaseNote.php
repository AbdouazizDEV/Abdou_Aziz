<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseNote extends FirebaseModel
{
    protected $fillable = [
        'apprenant_id', 'module_id', 'note'
    ];

    public static function getAll(): array
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('notes');
        return $reference->getSnapshot()->getValue() ?? [];
    }

    public static function findById($id): ?self
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('notes/' . $id);
        $data = $reference->getSnapshot()->getValue();

        if ($data) {
            $data['id'] = $id;
            return (new self())->fromArray($data);
        }

        return null;
    }

   /*  public function update(array $attributes = [])
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('notes/' . $this->id);
        $reference->update($attributes);
    } */

    public function delete()
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('notes/' . $this->id);
        $reference->remove();
    }

    public function fromArray(array $data): self
    {
        $this->apprenant_id = $data['apprenant_id'] ?? null;
        $this->module_id = $data['module_id'] ?? null;
        $this->note = $data['note'] ?? null;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'apprenant_id' => $this->apprenant_id,
            'module_id' => $this->module_id,
            'note' => $this->note,
        ];
    }
}
