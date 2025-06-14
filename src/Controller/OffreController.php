<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Candidature;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/offre')]
class OffreController extends AbstractController
{
    // ---------------------------------------------------------
    // 1) Liste des offres
    // ---------------------------------------------------------
    #[Route('/', name: 'offre_index', methods: ['GET'])]
    public function index(OffreRepository $repo): Response
    {
        return $this->render('offre/index.html.twig', [
            'offres' => $repo->findAll(),
        ]);
    }

    // ---------------------------------------------------------
    // 2) Création d’une nouvelle offre
    // ---------------------------------------------------------
    #[Route('/new', name: 'offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $offre = new Offre();
        $offre->setDatePublication(new \DateTime());

        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($offre);
            $em->flush();

            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/new.html.twig', [
            'form'  => $form->createView(),
            'offre' => $offre,
        ]);
    }

    // ---------------------------------------------------------
    // 3) Affichage détaillé
    // ---------------------------------------------------------
    #[Route('/{id}', name: 'offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    // ---------------------------------------------------------
    // 4) Édition
    // ---------------------------------------------------------
    #[Route('/{id}/edit', name: 'offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('offre_index');
        }

        return $this->render('offre/edit.html.twig', [
            'form'  => $form->createView(),
            'offre' => $offre,
        ]);
    }

    // ---------------------------------------------------------
    // 5) Suppression
    // ---------------------------------------------------------
    #[Route('/{id}', name: 'offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offre->getId(), $request->request->get('_token'))) {
            $em->remove($offre);
            $em->flush();
        }

        return $this->redirectToRoute('offre_index');
    }

    // ---------------------------------------------------------
    // 6) Postuler à une offre
    //     GET  -> simple redirection (évite le 404 si on tape l’URL)
    //     POST -> enregistre la candidature
    // ---------------------------------------------------------
    #[Route('/{id}/postuler', name: 'offre_postuler', methods: ['GET', 'POST'])]
    public function postuler(Request $request, Offre $offre, EntityManagerInterface $em): Response
    {
        // Si on arrive en GET → on renvoie vers la page détail
        if ($request->isMethod('GET')) {
            return $this->redirectToRoute('offre_show', ['id' => $offre->getId()]);
        }

        // On est en POST → on traite la candidature
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $exists = $em->getRepository(Candidature::class)
                     ->findOneBy(['offre' => $offre, 'user' => $user]);

        if ($exists) {
            $this->addFlash('warning', 'Vous avez déjà postulé à cette offre.');
        } else {
            $cand = (new Candidature())
                ->setOffre($offre)
                ->setUser($user)
                ->setDatePostulation(new \DateTime());

            $em->persist($cand);
            $em->flush();

            $this->addFlash('success', 'Votre candidature a bien été enregistrée.');
        }

        return $this->redirectToRoute('offre_show', ['id' => $offre->getId()]);
    }
}
