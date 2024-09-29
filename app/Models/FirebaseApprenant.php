<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirebaseApprenant extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'date_naissance', 'sexe', 'referentiel_id', 'photo', 'matricule', 'qr_code', 'is_active'
    ];

    public static function getAll($filters = []): array
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('apprenants');

        $snapshot = $reference->getSnapshot()->getValue() ?? [];

        // Apply filters if necessary

        return $snapshot;
    }

    public static function findById($id): ?self
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('apprenants/' . $id);
        $data = $reference->getSnapshot()->getValue();

        if ($data) {
            $data['id'] = $id;
            return (new self())->fromArray($data);
        }

        return null;
    }

    public function update(array $attributes = [], array $options = []): self
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('apprenants/' . $this->id);

        $reference->update($attributes);

        $updatedData = $reference->getSnapshot()->getValue();
        $this->fromArray($updatedData);

        return $this;
    }

    public function delete(): bool
    {
        $firebase = app('firebase.database');
        $reference = $firebase->getReference('apprenants/' . $this->id);

        $reference->remove();

        return true;
    }

    public function fromArray(array $data): self
    {
        $this->nom = $data['nom'] ?? null;
        $this->prenom = $data['prenom'] ?? null;
        $this->date_naissance = $data['date_naissance'] ?? null;
        $this->sexe = $data['sexe'] ?? null;
        $this->referentiel_id = $data['referentiel_id'] ?? null;
        $this->photo = $data['photo'] ?? null;
        $this->matricule = $data['matricule'] ?? null;
        $this->qr_code = $data['qr_code'] ?? null;
        $this->is_active = $data['is_active'] ?? true;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'date_naissance' => $this->date_naissance,
            'sexe' => $this->sexe,
            'referentiel_id' => $this->referentiel_id,
            'photo' => $this->photo,
            'matricule' => $this->matricule,
            'qr_code' => $this->qr_code,
            'is_active' => $this->is_active,
        ];
    }
}
