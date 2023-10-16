<?php

namespace App\Controller;

use App\Entity\PanierAchat;
use App\Form\PanierAchatType;
use App\Repository\PanierAchatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier/achat')]
class PanierAchatController extends AbstractController
{
    #[Route('/', name: 'app_panier_achat_index', methods: ['GET'])]
    public function index(PanierAchatRepository $panierAchatRepository): Response
    {
        return $this->render('panier_achat/index.html.twig', [
            'panier_achats' => $panierAchatRepository->findAll(),
        ]);
    }

    #[Route('/ajouter-au-panier', name: 'app_panier_achat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panierAchat = new PanierAchat();


        $nomProduit = $request->request->get('Nomproduit');
        $prixProduit = $request->request->get('prixProduit');
        

        // Créer une nouvelle instance de l'entité Produit
        
        $panierAchat->setNom($nomProduit);
        $panierAchat->setPrix($prixProduit);
        

        $entityManager->persist($panierAchat);
        $entityManager->flush();
        
        // Vous pouvez également renvoyer une réponse à la page web pour indiquer que l'ajout au panier a réussi
        return new Response('Produit ajouté au panier avec succès');

        
    }


   



    #[Route('/{id}', name: 'app_panier_achat_show', methods: ['GET'])]
    public function show(PanierAchat $panierAchat): Response
    {
        return $this->render('panier_achat/show.html.twig', [
            'panier_achat' => $panierAchat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PanierAchat $panierAchat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierAchatType::class, $panierAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier_achat/edit.html.twig', [
            'panier_achat' => $panierAchat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_achat_delete', methods: ['POST'])]
    public function delete(Request $request, PanierAchat $panierAchat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierAchat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($panierAchat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_achat_index', [], Response::HTTP_SEE_OTHER);
    }


    public function ajouterAuPanier(Request $request , EntityManagerInterface $entityManager ): Response
    {
        // Récupérer les données du produit depuis la requête
        $nomProduit = $request->request->get('Nomproduit');
        $prixProduit = $request->request->get('prixProduit');
        

        // Créer une nouvelle instance de l'entité Produit
        $panier = new PanierAchat();
        $panier->setNom($nomProduit);
        $panier->setPrix($prixProduit);
        

        // Vous pouvez également ajouter d'autres attributs de l'entité selon vos besoins

        // Persistez l'entité en base de données
        $entityManager->persist($panier);
        $entityManager->flush();
        
        // Vous pouvez également renvoyer une réponse à la page web pour indiquer que l'ajout au panier a réussi
        return new Response('Produit ajouté au panier avec succès');
    }
}
