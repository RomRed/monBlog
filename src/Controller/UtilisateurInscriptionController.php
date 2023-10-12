<?php
namespace App\Controller;

use App\Entity\Ville;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurInscriptionController extends AbstractController
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }
    #[Route('/utilisateur/inscription', name: 'app_utilisateur_inscription')]
    public function inscription(Request $request, VilleRepository $villeRepository, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher): Response
    {
        $utilisateur = new Utilisateur();
        
        // dd($utilisateur);
        $utilisateurForm = $this->createForm(UtilisateurType::class);
        $utilisateurForm->handleRequest($request);
        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {
            $nom = $utilisateurForm->get('nom')->getData();
            $prenom = $utilisateurForm->get('prenom')->getData();
            $email = $utilisateurForm->get('email')->getData();
            $codeInsee = $utilisateurForm->get('citycode')->getData();
            $mdp =  $utilisateurForm->get('mdp')->getData();
            $adresse = $utilisateurForm->get('adresse')->getData();
            $ipUser = $request->getClientIp();
            // dd($codeInsee);
            // Utilisez le repository pour trouver la ville par son code INSEE
            $ville = $villeRepository->findOneByCodeInsee($codeInsee);
            // $ville->getIdVille();
            // dd($ville);
            if ($ville) {
                $utilisateur->setNom($nom);
                $utilisateur->setPrenom($prenom);
                $utilisateur->setEmail($email);
                $hashedPassword = $passwordHasher->hashPassword($utilisateur, $mdp);
                $utilisateur->setMdp($hashedPassword);
                $utilisateur->setDateInscription(new \DateTime());
                $utilisateur->setCodeRoles("ROLE_USER");
                $utilisateur->setIdRoleUtilisateur(2);
                $utilisateur->setAdresse($adresse);
                $utilisateur->setIpInscription($ipUser);
                $utilisateur->setIdVille($ville->getIdVille()); // Assurez-vous que vous avez une méthode setVille dans votre entité Utilisateur
                $entityManager->persist($utilisateur);
                $entityManager->flush();

                return $this->redirectToRoute('app_auth');
            }
        }


        return $this->render('utilisateur_inscription/index.html.twig', [
            'utilisateurForm' => $utilisateurForm->createView(),
        ]);
    }
}
