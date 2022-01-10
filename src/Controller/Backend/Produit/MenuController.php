<?php

namespace App\Controller\Backend\Produit;


use App\Entity\Menu;
use App\Service\Generator\DigitalGenerator;
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
                if($complement !=null){
                    $menu->addComplement($complement);
                    $prix=  $complement->getPrix()+$prix;  
                }
                
                
            }
        }

         $prix=$burger->getPrix()+$prix;

         $menu->setNom($request->request->get('nom'));
         $menu->setPrix($prix);
         $menu->setTempsCuisson($burger->getTempsCuisson());
         $ref=$gen->generateRef('menu');
         $menu->setCode($ref);
         $menu->setBurger($burger);

         $errors = $validator->validate($menu);
            if (count($errors) > 0) {
              $this->addFlash(
                    'error_message',
                    'Veuillez verifier votre saissie'
              );
              return $this->redirectToRoute('menus');
            }
            $em->persist($menu);
            $em->flush();
            $var=$repoVar->find(1);
            $var->setLastIdMenu($var->getLastIdMenu()+1);
            $em->persist($var);
            $em->flush();
         
        return $this->redirectToRoute('menus');
    }

    /**
     * @Route("/produit/menu/edit/{code}", name="menus_edit")
     */
    public  function edit(BurgerRepository $repoBurger,ComplementRepository $repoComplement,MenuRepository  $repo,Request $request){
        $burgers=$repoBurger->findAll();
        $complements=$repoComplement->findAll();
        $menuSelect=$repo->findOneBy(['code' => $request->attributes->filter('code')]);
        return $this->render('backend/produit/menu/add.html.twig', [
            'burgers'=>$burgers,
            'complements'=>$complements,
            'menuSelect'=>$menuSelect
        ]);


    }
    /**
     * @Route("/produit/menu/update", name="menus_update")
     */
    public  function update(Request  $request,BurgerRepository $repoBurger,ComplementRepository $repoComplement,MenuRepository  $repo,EntityManagerInterface $em,ValidatorInterface $validator){

        //dd($request);
        $burger=$repoBurger->findOneBy(['code' => $request->request->get('burger')]);
        $codes=$request->request->get('complements');
        $prix=0;
        if($codes == null){
            return $this->redirectToRoute('menus_add');
        }else{
            $menu= $repo->findOneBy(['code' => $request->request->get('code')]);
            $complements=[];
            $prix=0;

            foreach ($codes as $code) {

                $complement=$repoComplement->findOneBy(['code' => $code]);
                $complements[]=$complement;
                $prix=  $complement->getPrix()+$prix;
            }
            $menu->setComplements($complements);
            $prix=$burger->getPrix()+$prix;
            $menu->setNom($request->request->get('nom'));
            $menu->setPrix($prix);
            $menu->setTempsCuisson($burger->getTempsCuisson());
            $menu->setBurger($burger);
            $errors = $validator->validate($menu);
            if (count($errors) > 0) {
                $this->addFlash(
                    'error_message',
                    'Veuillez verifier votre saissie'
                );
                return $this->redirectToRoute('menus');
            }
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute('menus');
        }
    }
    /**
     * @Route("/produit/menu/delete/{code}", name="menus_delete")
     */

    public function delete(EntityManagerInterface $em,Request $request,MenuRepository $repo)
    {
        $code=$request->attributes->filter('code');

        $menu=$repo->findOneBy(['code' => $code]);

        if($menu !=null){
            $em->remove($menu);
            $em->flush();
        }

        return $this->redirectToRoute('menus');
    }
}
