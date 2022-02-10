<?php

namespace App\Controller\Backend;

use DateTime;
use App\Entity\Gestionnaire;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GestionnaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 *
 * @IsGranted("ROLE_GESTIONNAIRE")
 */
class DashBoardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(CommandeRepository $repoCommande): Response
    {

        $date = new \DateTimeImmutable();
        $commandesDay=$repoCommande->findBy(["createAt"=>$date,"status" =>"TERMINER"]);
        $commandesEnCours=$repoCommande->findBy(["createAt"=>$date,"status" =>"en attente"]);
        $commandesEnAnnuler=$repoCommande->findBy(["createAt"=>$date,"status" =>"ANNULER"]);
        //dd($commandesDay);
        $totalJour=0;
        
        foreach ($commandesDay as $commande) {

            $totalJour=intval($totalJour+$commande->getTotal());
            /*foreach ($commande->getDetailCommandes() as $detail) {
                dd($detail);
            }*/
        }
        return $this->render('backend/dashboard/index.html.twig',[
            "commandeDay" =>$commandesDay,
            "totalJour" =>$totalJour,
            "commandesEnCours" => $commandesEnCours,
            "commandesEnAnnuler" => $commandesEnAnnuler
        ]);
    }


    /**
     * @Route("/gestion/user", name="user_list")
     */
    public function listUser(GestionnaireRepository $repo){

        $users = $repo->findAll();
        return $this->render('security/listUser.html.twig',[
            "users" =>$users,
            
        ]);
    }

     /**
     * @Route("/gestion/add/gestionnaire", name="app_gestionnaire")
     */

     public function addGestioonnaire(Request $request, EntityManagerInterface $em,UserPasswordHasherInterface $encoder){

       // dd($request);
       $gestionnaire= new Gestionnaire();
       $gestionnaire->setNom($request->request->get('nom'));
       $gestionnaire->setPrenom($request->request->get('prenom'));
       $gestionnaire->setEmail($request->request->get('email'));
       $gestionnaire->setMatricule("MAT".uniqid());
       $plainPassword = $request->request->get('password');
       $passwordEncode= $encoder->hashPassword($gestionnaire, $plainPassword);
       $gestionnaire->setPassword($passwordEncode);
       $em->persist($gestionnaire);
       $em->flush();
      return  $this->redirectToRoute("user_list");
     }


}
