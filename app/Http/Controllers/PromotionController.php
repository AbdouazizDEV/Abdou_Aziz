<?php

namespace App\Http\Controllers;

use App\Models\FirebaseReferentiel;
use Illuminate\Http\Request;
use App\Services\PromotionService;
use Carbon\Carbon;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    // Créer une promotion
    public function store(Request $request)
{
    $data = $request->validate([
        'libelle' => 'required|unique:promotions,libelle|max:255',
        'date_debut' => 'required|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut', // Date de fin est facultative
        'duree' => 'nullable|integer', // Durée en mois est facultative
        'referentiels' => 'required|array',
        'referentiels.*' => 'string', // Accepter des IDs sous forme de chaînes de caractères
        'photo' => 'nullable|string',
        'etat' => 'in:Inactif', // Par défaut, l'état est Inactif
    ]);

    // Si la durée est fournie mais pas la date de fin, on la calcule
    if (isset($data['duree']) && !isset($data['date_fin'])) {
        $data['date_fin'] = Carbon::parse($data['date_debut'])->addMonths($data['duree']);
    }

    // Si la date de fin est fournie mais pas la durée, on la calcule
    if (isset($data['date_fin']) && !isset($data['duree'])) {
        $data['duree'] = Carbon::parse($data['date_debut'])->diffInMonths(Carbon::parse($data['date_fin']));
    }

    // Créer la promotion avec les données validées
    $promotion = $this->promotionService->createPromotion($data);

    return response()->json($promotion, 201);
}


    // Lister toutes les promotions
    public function index(Request $request)
    {
        $etat = $request->get('etat');
        $promotions = $this->promotionService->getPromotions($etat);
        return response()->json($promotions);
    }

    // Afficher une promotion
    public function show($id)
    {
        $promotion = $this->promotionService->getPromotionById($id);
        return response()->json($promotion);
    }

    // Mettre à jour une promotion
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'libelle' => 'sometimes|unique:promotions,libelle,' . $id . '|max:255',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'sometimes|date|after_or_equal:date_debut',
            'duree' => 'sometimes|integer',
            'referentiels' => 'nullable|array|exists:referentiels,id',
            'photo' => 'nullable|string',
            'etat' => 'in:Actif,Cloturer,Inactif'
        ]);

        $promotion = $this->promotionService->updatePromotion($id, $data);
        return response()->json($promotion);
    }

    // Clôturer une promotion
    public function cloturer($id)
    {
        $promotion = $this->promotionService->cloturerPromotion($id);
        return response()->json($promotion);
    }


    // Changer l'état d'une promotion
    public function changeEtat(Request $request, $id)
    {
        
        $data = $request->validate([
            'etat' => 'required|in:Actif,Inactif',
        ]);
        //dd($data);
        $promotion = $this->promotionService->changeEtat($id, $data['etat']);
        return response()->json($promotion);
    }

    public function updateReferentiels(Request $request, $id)
    {
        // Valider que les référentiels sont bien un tableau d'IDs
        $data = $request->validate([
            'referentiels' => 'required|array',
            'referentiels.*' => 'string', // Accepter des IDs sous forme de chaînes de caractères
        ]);

        // Vérifier l'existence des référentiels dans Firebase
        foreach ($data['referentiels'] as $referentielId) {
            if (!FirebaseReferentiel::findById($referentielId)) {
                return response()->json([
                    'message' => "Référentiel avec ID $referentielId non trouvé.",
                    'errors' => ['referentiels' => ["Le référentiel avec l'ID $referentielId est invalide."]]
                ], 422);
            }
        }

        // Si tous les référentiels sont valides, mettre à jour la promotion
        $promotion = $this->promotionService->updateReferentiels($id, $data['referentiels']);
        return response()->json($promotion);
    }

}
