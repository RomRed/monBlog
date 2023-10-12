<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class ViewArticleController extends AbstractController
{
    #[Route('/view/article/{id}', name: 'app_view_article')]
    public function index(ArticleRepository $repoArticle, $id, ImageRepository $repoImg,UserInterface $user): Response
    {
        $articles = $repoArticle->find($id);

       
        $images = [];

        foreach ($articles as $article) {
            $articleImages = $repoImg->findBy(['idArticle' => $article]);
            $images[$article->getIdArticle()] = $articleImages;
        }

        return $this->render('view_article/index.html.twig', [
            'controller_name' => 'ViewArticleController',
            'articles' => $articles,
            'images' => $images,
        ]);
    }
}
