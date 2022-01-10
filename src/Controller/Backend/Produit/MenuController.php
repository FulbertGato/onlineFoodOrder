<?php

namespace App\Controller\Backend\Produit;

use DateTime;
use App\Entity\Menu;
use App\Service\DigitalGenerator;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConfigurationVarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    /**
     * @Route("/produit/menu", name="menus")
     */
    public function index(MenuRepository $repo): Response
    {
        $menus=$repo->findAll();
        return $this->render('backend/produit/menu/index.html.twig', [
            'menus'=>$menus
           
        ]);
    }

    /**
     * @Route("/produit/menu/add", name="menus_add")
     */
    public function add(BurgerRepository $repoBurger,ComplementRepository $repoComplement): Response
    {
        $burgers=$repoBurger->findAll();
        $complements=$repoComplement->findAll();
        return $this->render('backend/produit/menu/add.html.twig', [
            'burgers'=>$burgers,
            'complements'=>$complements
           
        ]);
    }

    /**
     * @Route("/produit/menu/store", name="menus_store")
     */
    public function store(Request $request, BurgerRepository $repoBurger,ComplementRepository $repoComplement,DigitalGenerator $gen,ConfigurationVarRepository $repoVar,EntityManagerInterface $em,ValidatorInterface $validator): Response
    {
        $burger=$repoBurger->findOneBy(['code' => $request->request->get('burger')]);
        $codes=$request->request->get('complements');
        
        $prix=0;
        if($codes == null){
            return $this->redirectToRoute('menus_add');
        }else{
            $menu= new Menu();
            foreach ($codes as $code) {

                $complement=$repoComplement->findOneBy(['code' => $code]);
                //$complements[]=$complement;
                if($complement !=null){
                    $menu->addComplement($complement);
                    $prix=  $complement->getPrix()+$prix;  
                }
                
                
            }
        }
         // $complement=$repoComplement->findAll();
         // dd( $complements);
         $prix=$burger->getPrix()+$prix;
         $time= new DateTime($request->request->get('temps'));
         $menu->setNom($request->request->get('nom'));
         $menu->setPrix($prix);
         $menu->setTempsCuisson($burger->getTempsCuisson());
         $ref=$gen->generateRef('menu');
         $menu->setCode($ref);
         $menu->setBurger($burger);

         $errors = $validator->validate($menu);

           // dd($ref);
            if (count($errors) > 0) {

              dd($errors);
              $this->addFlash(
                    'error_message',
                    'Veuillez verifier votre saissie'
                );
                return $this->redirectToRoute('complement');
            }
            $em->persist($menu);
            $em->flush();
            $var=$repoVar->find(1);
            $var->setLastIdMenu($var->getLastIdMenu()+1);
            $em->persist($var);
            $em->flush();
         
        return $this->redirectToRoute('menus');
    }
}
