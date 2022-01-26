<?php

namespace App\Controller\Backend\Produit;

use DateTime;
use App\Entity\Burger;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Generator\DigitalGenerator;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ConfigurationVarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *
 * @IsGranted("ROLE_GESTIONNAIRE")
 */
class BurgerController extends AbstractController
{
    /**
     * @Route("/produit/burger", name="burgers")
     */
    public function index(BurgerRepository $repo): Response
    {
        $burgers= $repo->findAll();
        return $this->render('backend/produit/burger/index.html.twig', [
            'burgers'=>$burgers
            
        ]);
    }

    /**
     * @Route("produit/burger/save", name="burger_save",methods={"POST"})
     * @throws \Exception
     */
    public function save(Request $request,EntityManagerInterface $em,ValidatorInterface $validator,BurgerRepository $repo,DigitalGenerator $gen,ConfigurationVarRepository $repoVar){


        $burger= new Burger();
        if($request->request->has('btn_save')){    
            $burger = $this->createBurgerObjet($request,$burger);
            $burger->setCode($gen->generateRef('burger'));
            $errors = $validator->validate($burger);
            if (count($errors) > 0) { dd($errors); }
            $var=$repoVar->find(1);
            $var->setLastIdBurger($var->getLastIdBurger()+1);
            $em->persist($var);
        }elseif ($request->request->has('btn_edit')){
            $burger= $repo->findOneBy(['code' => $request->request->get('code')]);
            $burger = $this->createBurgerObjet($request,$burger); 

            $errors = $validator->validate($burger);
            if (count($errors) > 0) {dd($errors);}
            
        }
        $em->persist($burger);
        $em->flush();
        return $this->redirectToRoute('burgers');



    }
    /**
     * @Route("produit/burgers/edit/{code}", name="burger_edit",methods={"get"})
     */
    public function edit(Request $request,BurgerRepository $repo)
    {

        $code=$request->attributes->filter('code');
        $burgerSelect=$repo->findOneBy(['code' => $code]);
        $burgers=$repo->findAll();  
        return $this->render('backend/produit/burger/index.html.twig', [
            'burgers' => $burgers,
            'burgerSelect'=>$burgerSelect
        ]);
    }

     /**
     * @Route("burger/archiver/{code}", name="burger_del",methods={"get"})
     */
    public function archiver(EntityManagerInterface $em,Request $request,BurgerRepository $repo)
    {
            $code=$request->attributes->filter('code');
            $burger=$repo->findOneBy(['code' => $code]);
            if($burger->getEtat() == 0 ){
                $burger->setEtat(1);
                foreach ($burger->getMenus() as $menu) {
                    
                        $menu->setEtat(1);
                }
                }else{               
                    $burger->setEtat(0);
                    foreach ($burger->getMenus() as $menu) {                  
                        $menu->setEtat(0);
                    }
                }
            $em->persist($burger);
            $em->flush();
        
        return $this->redirectToRoute('burgers');
    }


    public function createBurgerObjet($request,$burger){

            $time= new DateTime($request->request->get('temps'));
            
            $burger ->setNom($request->request->get('nom')) 
            ->setPrix(floatval($request->request->get('prix')))
            ->setDetail($request->request->get('detail'))
            ->setTempsCuisson($time)
            ->setEtat($request->request->get('etat'))
            ->setImage($this->imageCreate($request));

            return $burger;

    }

    public function imageCreate($request){
        $image=$request->files->get('image');
        if($image==null){

                 
            $fichier="Default.jpg";

         }else{
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('images_directory')."/burgers",
                $fichier
            );
         }
        
        return $fichier;
    }

    public function imageDelete($name){
        if($name != "Default.jpg" ){
            unlink($this->getParameter('images_directory').'/burgers/'.$name);
        }
    }


}
