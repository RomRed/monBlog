<?php

namespace App\Controller;

use DateTime;
use App\Entity\Image;
use App\Entity\Article;
use App\Form\ImageType;
use App\Form\ArticleType;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreeArticleController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/cree/article', name: 'app_cree_article')]
    public function index(Request $request, UserInterface $user): Response
    {
        $image = new Image();
        $article = new Article();
        // dd($user);
        $form = $this->createForm(ArticleType::class, $article);
        $imgForm = $this->createForm(ImageType::class, $image);
        //ecouteur d'evenement 
        $form->handleRequest($request);
        $imgForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
      
            
            $slugify = new Slugify();
            $slug = $slugify->slugify($article->getTitreArticle()); 
            $article->setSlugArticle($slug);
            // $contentWithoutTags = strip_tags($article->getContenuArticle());
            // $article->setContenuArticle($contentWithoutTags);
            $article->setDateCreation(new DateTime());
            $article->setIdUtilisateur($user);
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

        return $this->redirectToRoute('app_dashboard', ['id' => $article->getIdArticle()]);
        // return new JsonResponse(['message' => 'Votre article a été créé avec succès']);
    }

 if ($imgForm->isSubmitted() && $imgForm->isValid()) {
            $imageFile = $imgForm->get('nom_image')->getData();
            $nomImageUnique = time() . '-' . $imageFile->getClientOriginalName();

            // Définir le nom de l'image dans l'objet Image
            $image->setNomImage($nomImageUnique);

            // Assurez-vous d'attribuer l'ID de l'article à l'image
            $article->getIdArticle();
                $image->setIdArticle($article->getIdArticle());
            

            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($image);
            $entityManager->flush();
            
        }
        return $this->render('cree_article/index.html.twig', [
            'controller_name' => 'CreeArticleController',
            'form' => $form->createView(),
            'imgForm' => $imgForm->createview(),
        ]);
    }
}
