<?php

namespace App\Controller\Backend\Produit;

use App\Entity\Burger;
use App\Repository\BurgerRepository;
use App\Repository\ConfigurationVarRepository;
use App\Service\Generator\DigitalGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BurgerController extends AbstractController
{
    /**
     * @Route("/backend/produit/burger", name="burgers")
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
     */
    public function save(Request $request,EntityManagerInterface $em,ValidatorInterface $validator,BurgerRepository $repo,DigitalGenerator $gen,ConfigurationVarRepository $repoVar){



        if($request->request->has('btn_save')){

            $burger= new Burger();
            $burger ->setNom($request->request->get('nom'));
            $burger ->setPrix(floatval($request->request->get('prix')));
            $code=$gen->generateRef('burger');
            $burger->setCode($code);
            $burger->setDetail($request->request->get('detail'));
            $time= new DateTime($request->request->get('temps'));
            $burger->setTempsCuisson($time);
            $errors = $validator->validate($burger);
            if (count($errors) > 0) {

                dd($errors);
                $this->addFlash(
                      'error_message',
                      'Veuillez verifier votre saissie'
                  );
                  return $this->redirectToRoute('burgers');
              }
            $em->persist($burger);
            $em->flush();
            $var=$repoVar->find(1);
            $var->setLastIdBurger($var->getLastIdBurger()+1);
            $em->persist($var);
            $em->flush();
        }elseif ($request->request->has('btn_edit')){
            $code=$request->request->get('code');
            $burger= $repo->findOneBy(['code' => $code]);
            $burger ->setNom($request->request->get('nom'));
            $burger ->setPrix(floatval($request->request->get('prix')));
            $burger->setDetail($request->request->get('detail'));
            $time= new DateTime($request->request->get('temps'));
            $burger->setTempsCuisson($time);
            $errors = $validator->validate($burger);
            if (count($errors) > 0) {

                dd($errors);
                $this->addFlash(
                      'error_message',
                      'Veuillez verifier votre saissie');
                  return $this->redirectToRoute('burgers');
            }
            $em->persist($burger);
            $em->flush();
            


        }
        return $this->redirectToRoute('burgers');



    }
    /**
     * @Route("burgers/edit/{code}", name="burger_edit",methods={"get"})
     */
    public function edit(Request $request,BurgerRepository $repo)
    {

        $code=$request->attributes->filter('code');
       // dd($code);
        $burgerSelect=$repo->findOneBy(['code' => $code]);
       // dd($complementSelect);
        $burgers=$repo->findAll();
        
        return $this->render('backend/produit/burger/index.html.twig', [
            'burgers' => $burgers,
            'burgerSelect'=>$burgerSelect
        ]);
    }

     /**
     * @Route("burger/delete/{code}", name="burger_del",methods={"get"})
     */
    public function delete(EntityManagerInterface $em,Request $request,BurgerRepository $repo)
    {
            $code=$request->attributes->filter('code');
           // dd($code);
            $burger=$repo->findOneBy(['code' => $code]);

            $em->remove($burger);
            $em->flush();
        
        return $this->redirectToRoute('burgers');
    }


}
