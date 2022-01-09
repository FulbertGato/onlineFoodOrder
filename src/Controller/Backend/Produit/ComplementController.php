<?php

namespace App\Controller\Backend\Produit;

use App\Entity\Complement;
use App\Repository\ComplementRepository;
use App\Repository\ConfigurationVarRepository;
use App\Repository\TypeComplementRepository;
use App\Service\DigitalGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ComplementController extends AbstractController
{
    /**
     * @Route("produit/complement", name="complements")
     */
    public function index(ComplementRepository $repo,TypeComplementRepository $repoType): Response
    {
        $complements=$repo->findAll();
       
        $typescomplements = $repoType->findAll();
        return $this->render('backend/produit/complement/index.html.twig', [
            'complements' => $complements,
            "types" =>  $typescomplements
        ]);
    }


   /**
     * @Route("produit/complement/s", name="complement_save",methods={"POST"})
     */
    public function save(Request $request,EntityManagerInterface $em,ValidatorInterface $validator,ComplementRepository $repo,TypeComplementRepository $repoType,DigitalGenerator $gen,ConfigurationVarRepository $repoVar)
    {

        if($request->request->has('btn_save')){


            if(empty($request->request->get('image'))){

                $request->request->set('image','defaultImg');

            }
            $type= $repoType->find($request->request->get('type'));


            $complement = new Complement();
            
            $complement ->setNom($request->request->get('nom'));
            $complement ->setPrix(floatval($request->request->get('prix')));
            $complement ->setTypeComplement($type);
            $code=$gen->generateRef('complement');
            $complement->setCode($code);
            $errors = $validator->validate($complement);

           // dd($ref);
            if (count($errors) > 0) {

              dd($errors);
              $this->addFlash(
                    'error_message',
                    'Veuillez verifier votre saissie'
                );
                return $this->redirectToRoute('complement');
            }
            $em->persist($complement);
            $em->flush();
            $var=$repoVar->find(1);
            $var->setLastIdComplement($var->getLastIdComplement()+1);
            $em->persist($var);
            $em->flush();


        }
        elseif ($request->request->has('btn_edit')){
            $code=$request->request->get('code');
            $complement= $repo->findOneBy(['code' => $code]);
            $type= $repoType->find($request->request->get('type'));
            $complement ->setNom($request->request->get('nom'));
            $complement ->setPrix($request->request->get('prix'));
            $complement ->setTypeComplement($type);
            $errors = $validator->validate($complement);
            if (count($errors) > 0) {
              dd($errors);
                $this->addFlash(
                    'error_message',
                    'Veuillez verifier votre saissie'
                );
                return $this->redirectToRoute('complement');
            }

            $em->persist($complement);
            $em->flush();


        }
        return $this->redirectToRoute('complements');



    }

    /**
     * @Route("complement/edit/{code}", name="complement_edit",methods={"get"})
     */
    public function edit(Request $request,ComplementRepository $repo,TypeComplementRepository $repoType)
    {

        $code=$request->attributes->filter('code');
       //dd($code);
        $complementSelect=$repo->findOneBy(['code' => $code]);
       //dd($complementSelect);
        $complements=$repo->findAll();
        $types=$repoType->findAll();
        return $this->render('backend/produit/complement/index.html.twig', [
            'complements' => $complements,
            'types'=>$types,'complementSelect'=>$complementSelect
        ]);
    }

    /**
     * @Route("complement/delete/{code}", name="complement_del",methods={"get"})
     */
    public function delete(EntityManagerInterface $em,Request $request,ComplementRepository $repo)
    {
            $code=$request->attributes->filter('code');
           // dd($code);
            $complement=$repo->findOneBy(['code' => $code]);

            $em->remove($complement);
            $em->flush();
        
        return $this->redirectToRoute('complements');
    }



}
