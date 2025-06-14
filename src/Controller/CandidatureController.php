<?php

namespace App\Controller;

use App\Repository\CandidatureRepository;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidatures')]
class CandidatureController extends AbstractController
{
    /**
     * Affiche toutes les candidatures.
     */
    #[Route('/', name: 'candidature_index', methods: ['GET'])]
    public function index(CandidatureRepository $repo): Response
    {
        $candidatures = $repo->findAll();

        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    /**
     * Affiche les candidatures liées à une offre donnée.
     */
    #[Route('/offre/{id}', name: 'candidature_par_offre', methods: ['GET'])]
    public function parOffre(int $id, OffreRepository $offreRepo): Response
    {
        $offre = $offreRepo->find($id);
        if (!$offre) {
            throw $this->createNotFoundException('Offre introuvable');
        }

        // Récupère directement la collection de candidatures via la relation OneToMany
        $candidatures = $offre->getCandidatures();

        return $this->render('candidature/par_offre.html.twig', [
            'offre' => $offre,
            'candidatures' => $candidatures,
        ]);
    }
}


