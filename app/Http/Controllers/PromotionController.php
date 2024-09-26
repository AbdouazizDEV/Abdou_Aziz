<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PromotionService;

class PromotionController extends Controller
{
    protected $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => 'required|unique:promotions,libelle|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'duree' => 'nullable|integer',
            'photo' => 'nullable|string',
            'etat' => 'in:Actif,Cloturer,Inactif'
        ]);

        $promotion = $this->promotionService->createPromotion($data);
        return response()->json($promotion, 201);
    }

    public function index(Request $request)
    {
        $etat = $request->get('etat');
        $promotions = $this->promotionService->getPromotions($etat);
        return response()->json($promotions);
    }

    public function show($id)
    {
        $promotion = $this->promotionService->getPromotionById($id);
        return response()->json($promotion);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'libelle' => 'sometimes|unique:promotions,libelle,' . $id . '|max:255',
            'date_debut' => 'sometimes|date',
            'date_fin' => 'sometimes|date|after_or_equal:date_debut',
            'duree' => 'sometimes|integer',
            'photo' => 'nullable|string',
            'etat' => 'in:Actif,Cloturer,Inactif'
        ]);

        $promotion = $this->promotionService->updatePromotion($id, $data);
        return response()->json($promotion);
    }

    public function destroy($id)
    {
        $this->promotionService->deletePromotion($id);
        return response()->json(['message' => 'Promotion supprimée avec succès']);
    }
}
