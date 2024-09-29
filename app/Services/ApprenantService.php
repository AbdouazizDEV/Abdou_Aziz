<?php
namespace App\Services;

use App\Repositories\Contracts\ApprenantRepositoryInterface;

class ApprenantService
{
    protected $apprenantRepository;

    public function __construct(ApprenantRepositoryInterface $mysqlApprenantRepository, ApprenantRepositoryInterface $firebaseApprenantRepository)
    {
        $this->apprenantRepository = env('APPRENANT_DATA_SOURCE', 'mysql') === 'firebase'
            ? $firebaseApprenantRepository
            : $mysqlApprenantRepository;
    }

    public function createApprenant(array $data)
    {
        $data['matricule'] = $this->generateMatricule();
        $data['qr_code'] = $this->generateQrCode($data);

        return $this->apprenantRepository->create($data);
    }

    public function importApprenants($file)
    {
        return $this->apprenantRepository->import($file);
    }

    public function getApprenants(array $filters)
    {
        return $this->apprenantRepository->all($filters);
    }

    public function getApprenantById($id)
    {
        return $this->apprenantRepository->find($id);
    }

    public function getInactiveApprenants()
    {
        return $this->apprenantRepository->getInactive();
    }

    public function relanceApprenants(array $ids)
    {
        foreach ($ids as $id) {
            $this->relanceApprenant($id);
        }
    }

    public function relanceApprenant($id)
    {
        // Job to send the email
    }

    protected function generateMatricule()
    {
        return 'APPR-' . strtoupper(uniqid());
    }

    protected function generateQrCode(array $data)
    {
        // Generate a QR code based on the apprenant's data
    }
}
