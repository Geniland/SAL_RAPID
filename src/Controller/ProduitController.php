<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/produit')]
class ProduitController extends AbstractController
{

    private $entityManager;
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }






#[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, ProduitRepository $produitRepository  , CategorieRepository $categorieRepository): Response
{
    $produit = new Produit();
    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion de l'upload d'images
        $images = $form->get('images')->getData();

        foreach ($images as $image) {
            // Générez un nom de fichier unique
            $fileName = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier dans le répertoire où vous souhaitez le stocker
            try {
                $image->move(
                    'public/image',
                    $fileName
                );
            } catch (FileException $e) {
                // Gérer l'exception en cas d'échec de l'upload
                // ...
            }

            // Stockez le nom du fichier dans l'entité Produit
            $produit->addImage($fileName);
        }

        // Enregistrez les autres données du produit
        $entityManager->persist($produit);
        $entityManager->flush();

        // return $this->redirectToRoute('app_produit_showAdmin', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('produit/new.html.twig', [
        'produits' => $produitRepository->findAll(),
        
        
        'form' => $form->createView(),
    ]);



}

#[Route('/Admin', name: 'app_produit_showAdmin', methods: ['GET'])]
public function indexAdmin(ProduitRepository $produitRepository): Response
{
    $produit = new Produit();
    return $this->render('produit/indexAdmin.html.twig', [
        'produits' => $produitRepository->findAll(),
    ]);
}






#[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
public function show(Produit $produit): Response
{
    return $this->render('produit/show.html.twig', [
        'produit' => $produit,
    ]);
}




#[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
        $entityManager->remove($produit);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_produit_new', [], Response::HTTP_SEE_OTHER);
}


#[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
{
    // Stockez les noms des anciennes images
    $oldImages = $produit->getImages();

    $form = $this->createForm(ProduitType::class, $produit);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Supprimez les anciennes images du système de fichiers
        foreach ($oldImages as $oldImage) {
            $filePath = 'public/image' . $oldImage;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Videz la collection d'images existante
        foreach ($produit->getImages() as $image) {
            $produit->removeImage($image);
        }

        // Ajoutez les nouvelles images
        $images = $form->get('images')->getData();
        foreach ($images as $image) {
            // Générez un nom de fichier unique
            $fileName = md5(uniqid()).'.'.$image->guessExtension();

            // Déplacez le fichier dans le répertoire où vous souhaitez le stocker
            try {
                $image->move(
                    'public/image',
                    $fileName
                );
            } catch (FileException $e) {
                  // Gérer l'exception en cas d'échec de l'upload
                // Par exemple, vous pouvez enregistrer un message d'erreur et rediriger l'utilisateur
                $this->addFlash('error', 'L\'upload de l\'image a échoué.');
                return $this->redirectToRoute('app_produit_edit', ['id' => $produit->getId()]);
            
            }

            // Ajoutez le nom du fichier à l'entité Produit
            $produit->addImage($fileName);
        }

        // Enregistrez les autres données du produit
        $entityManager->persist($produit);
        $entityManager->flush();

        return $this->redirectToRoute('app_produit_new', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('produit/edit.html.twig', [
        'produit' => $produit,
        'form' => $form,
    ]);
}





// ...

}
