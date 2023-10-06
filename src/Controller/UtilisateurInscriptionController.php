<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurInscriptionController extends AbstractController
{
    #[Route('/utilisateur/inscription', name: 'app_utilisateur_inscription')]
    public function index(): Response
    {
        return $this->render('utilisateur_inscription/index.html.twig', [
            'controller_name' => 'UtilisateurInscriptionController',
        ]);
    }
}
