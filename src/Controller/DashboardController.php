<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(UserInterface $user, ArticleRepository $repoArticle, ImageRepository $repoImg): Response
    {
        $articles = $repoArticle->findByIdUtilisateur($user);
        $images = [];

        foreach ($articles as $article) {
            $articleImages = $repoImg->findBy(['idArticle' => $article]);
            $images[$article->getIdArticle()] = $articleImages;
        }



        return $this->render('dashboard/index.html.twig', [
            'controller_name' => $user->getPrenom(),
            'articles' => $articles,
            'images' => $images,
        ]);
    }
}
