<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/commande')]
class AdminCommandeController extends AbstractController
{
    #[Route('/', name: 'admin_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin_commande/index.html.twig', [
            'commandes' => $commandeRepository->findBy([], ['date' => 'DESC']),
        ]);
    }

    #[Route('/{id}', name: 'admin_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin_commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/validate', name: 'admin_commande_validate', methods: ['POST'])]
    public function validate(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('validate'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $commande->setStatut(true);
            $entityManager->flush();

            $this->addFlash('success', 'La commande a été validée avec succès !');
        }

        return $this->redirectToRoute('admin_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/invalidate', name: 'admin_commande_invalidate', methods: ['POST'])]
    public function invalidate(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('invalidate'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $commande->setStatut(false);
            $entityManager->flush();

            $this->addFlash('success', 'La commande a été remise en attente !');
        }

        return $this->redirectToRoute('admin_commande_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'admin_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commande);
            $entityManager->flush();

            $this->addFlash('success', 'La commande a été supprimée avec succès !');
        }

        return $this->redirectToRoute('admin_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}