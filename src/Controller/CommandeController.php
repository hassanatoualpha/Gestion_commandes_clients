<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Client;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository, ClientRepository $clientRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Get current user's client
        $client = $clientRepository->findOneBy(['user' => $this->getUser()]);
        
        if (!$client) {
            $this->addFlash('error', 'Aucun profil client trouvé pour cet utilisateur.');
            return $this->redirectToRoute('app_home');
        }

        $commandes = $commandeRepository->findByClient($client);

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ClientRepository $clientRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Get current user's client
        $client = $clientRepository->findOneBy(['user' => $this->getUser()]);
        
        if (!$client) {
            $this->addFlash('error', 'Aucun profil client trouvé pour cet utilisateur.');
            return $this->redirectToRoute('app_home');
        }

        $commande = new Commande();
        $commande->setClient($client);
        
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commande);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commande a été créée avec succès !');

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande, ClientRepository $clientRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Check if user owns this order
        $client = $clientRepository->findOneBy(['user' => $this->getUser()]);
        if ($commande->getClient() !== $client) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accéder à cette commande.');
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }
}