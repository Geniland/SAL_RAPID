<?php

namespace App\Controller;

use App\Entity\PanierAchat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_produit_panier', methods: ['POST','GET'])]
    public function ajouterAuPanier(Request $request, EntityManagerInterface $entityManager)
    {
       


        $nomProduit = $request->request->get('nom_du_produit');
        $prixProduit = $request->request->get('prix_du_produit');
        $imgProduit = $request->request->get('image_du_produit');
        

        // Vérifiez si les données nécessaires sont présentes
       
        $panier = new PanierAchat();
        $panier->setNom($nomProduit);
        $panier->setPrix($prixProduit);
        $panier->setImage($imgProduit);
       

        // Persistez l'entité en base de données
        $entityManager->persist($panier);
        $entityManager->flush();

        return new Response('Produit ajouté au panier avec succès');


        // if ($request->isXmlHttpRequest()) {
        //     // Traiter l'ajout du produit en base de données
        //     $nomProduit = $request->request->get('nom_du_produit');
        //     $prixProduit = $request->request->get('prix_du_produit');
        //     $imgProduit = $request->request->get('image_du_produit');


        //     $panier = new PanierAchat();
        //     $panier->setNom($nomProduit);
        //     $panier->setPrix($prixProduit);
        //     $panier->setImage($imgProduit);
           
    
        //     // Persistez l'entité en base de données
        //     $entityManager->persist($panier);
        //     $entityManager->flush();
        
        //     // ... Code pour ajouter le produit à la base de données ...
        
        //     return new JsonResponse(['message' => 'Produit ajouté au panier avec succès']);
        // } else {
        //     // C'est une soumission de formulaire normale, rediriger ou faire le traitement nécessaire ici
        // }
       
        
        
        
        
        

          
    }
}
