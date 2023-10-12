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
        //ecouteur d'evenement 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($article->getTitreArticle()); 
            $article->setSlugArticle($slug);
          
            $article->setDateCreation(new DateTime());
            $article->setIdUtilisateur($user);
       
        
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $fichiers= $form->get('nom_image')->getData();
            foreach($fichiers as $fichier){
            if ($fichier) {
                
                $nomFichier = uniqid() . '.' . $fichier->guessExtension();
                $fichier->move(
                    $this->getParameter('brochures_directory'), // Dossier (public/uploads)
                    $nomFichier
                );
                $image = new Image();
                $image->setNomImage($nomFichier);
                $image->setIdArticle($article);
                $entityManager->persist($image);
                $entityManager->flush();
            }
            }
            
            $image->setIdArticle($article);
        return $this->redirectToRoute('app_dashboard', ['id' => $article->getIdArticle()]);
        // return new JsonResponse(['message' => 'Votre article a été créé avec succès']);
    }

        return $this->render('cree_article/index.html.twig', [
            'controller_name' => 'CreeArticleController',
            'form' => $form->createView(),

        ]);
    }
}
