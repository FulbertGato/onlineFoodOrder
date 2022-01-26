<?php

namespace App\Controller\Backend\Produit;

use App\Entity\TypeComplement;
use App\Repository\TypeComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TypeComplementController extends AbstractController
{
    /**
     * @Route("produit/type/complement", name="type_complement")
     */
    public function index(TypeComplementRepository $repo): Response
    {
        $typescomplements = $repo->findAll();
        return $this->render('backend/produit/type_complement/index.html.twig', [
           "types" =>  $typescomplements   ]);
    }

    /**
     * @Route("produit/type/complement/save", name="type_complement_save",methods={"POST"})
     */
    public function save(Request $request,EntityManagerInterface $em,ValidatorInterface $validator,TypeComplementRepository $repo)
    {
        if($request->request->has('btn_save')){
            $image=$request->files->get('image');
            $type = new TypeComplement();


              if($image==null){

                 
                   $type->setImage("Default.jpg");

                }else{
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move(
                        $this->getParameter('images_directory')."/typeComplements",
                        $fichier
                    );
                    $type->setImage($fichier);


                }
                $type->setNom($request->request->get('nom'));
                $errors = $validator->validate($type);

                if (count($errors) > 0) {

                    $this->addFlash(
                        'error_message',
                        'Veuillez verifier votre saissie'
                    );
                    return $this->redirectToRoute('type_complement');
                }
                $em->persist($type);
                $em->flush();

        }elseif ($request->request->has('btn_edit')){
            $type= $repo->find($request->request->get('id'));
            $type->setNom($request->request->get('nom'));
            $errors = $validator->validate($type);
            if (count($errors) > 0) {

                $this->addFlash(
                    'error_message',
                    'Veuillez verifier votre saissie'
                );
                return $this->redirectToRoute('type_complement');
            }

            $em->persist($type);
            $em->flush();


        }
        return $this->redirectToRoute('type_complement');



    }

     /**
     * @Route("produit/complement/type/edit/{id}", name="type_complement_edit",methods={"get"})
     */
    public function edit(TypeComplementRepository $repoType,TypeComplement $typeSelect)
    {
        $typescomplements = $repoType->findAll();
        return $this->render('backend/produit/type_complement/index.html.twig', [
            "types" =>  $typescomplements , "typeSelect"=>$typeSelect  ]);
    }
    /**
     * @Route("produit/complement/t/d/{id}", name="type_complement_del",methods={"get"})
     */
    public function delete(EntityManagerInterface $em,TypeComplement $typeSelect)
    {
        if(count($typeSelect->getComplements())>0){
            $this->addFlash(
                'error_message',
                'Cet Type  contient des Complements' );

        }else{
            if($typeSelect->getImage() != "Default.jpg"){
                unlink($this->getParameter('images_directory').'/typeComplements/'.$typeSelect->getImage());
            }
            $em->remove($typeSelect);
            $em->flush();
        }
        return $this->redirectToRoute('type_complement');
    }
}
