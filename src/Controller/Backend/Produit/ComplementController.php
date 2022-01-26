<?php

namespace App\Controller\Backend\Produit;

use App\Entity\Complement;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Generator\DigitalGenerator;
use App\Repository\TypeComplementRepository;
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
        $type= $repoType->find($request->request->get('type'));
        $complement = new Complement();
        if($request->request->has('btn_save')){
            $complement=$this->createComplementObjet($request,$complement,$type);
            $complement->setCode($gen->generateRef('complement'));
            $errors = $validator->validate($complement);
            if (count($errors) > 0) {dd($errors);}
            $em->persist($complement);
            $var=$repoVar->find(1);
            $var->setLastIdComplement($var->getLastIdComplement()+1);
            $em->persist($var);
        }
        elseif ($request->request->has('btn_edit')){
            $code=$request->request->get('code');
            $complement= $repo->findOneBy(['code' => $code]);
            $type= $repoType->find($request->request->get('type'));
            $complement=$this->createComplementObjet($request,$complement,$type);
            $errors = $validator->validate($complement);
            if (count($errors) > 0) {dd($errors);}
            $em->persist($complement);
         }
        $em->flush();
        return $this->redirectToRoute('complements');
    }

    /**
     * @Route("produit/complement/edit/{code}", name="complement_edit",methods={"get"})
     */
    public function edit(Request $request,ComplementRepository $repo,TypeComplementRepository $repoType)
    {
        $code=$request->attributes->filter('code');
        $complementSelect=$repo->findOneBy(['code' => $code]);
        $complements=$repo->findAll();
        $types=$repoType->findAll();
        return $this->render('backend/produit/complement/index.html.twig', [
            'complements' => $complements,
            'types'=>$types,'complementSelect'=>$complementSelect
        ]);
    }

    /**
     * @Route("produit/complement/archiver/{code}", name="complement_del",methods={"get"})
     */
    public function archiver(EntityManagerInterface $em,Request $request,ComplementRepository $repo)
    {
            $code=$request->attributes->filter('code');
            $complement=$repo->findOneBy(['code' => $code]);
            if($complement->getEtat() == 0 ){
                $complement->setEtat(1);
                foreach ($complement->getMenus() as $menu) {
                    $menu->setPrix($menu->getPrix() - $complement->getPrix());
                }
            }else{
                $complement->setEtat(0);
                foreach ($complement->getMenus() as $menu) {
                    $menu->setPrix($menu->getPrix() + $complement->getPrix());
                }
            }
            $em->persist($complement);
            $em->flush();
        
        return $this->redirectToRoute('complements');
    }


    public function createComplementObjet($request,$complement,$type){
        $complement->setNom($request->request->get('nom')) 
        ->setPrix(floatval($request->request->get('prix')))
        ->setDetail($request->request->get('detail'))
        ->setImage($this->imageCreate($request))
        ->setTypeComplement($type)
        ->setEtat($request->request->get('etat'));

        return $complement;
    }
    public function imageCreate($request){
        $image=$request->files->get('image');
        if($image==null){      
            $fichier="Default.jpg";
         }else{
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('images_directory')."/complements",
                $fichier
            );
         }
        return $fichier;
    }

    public function imageDelete($name){
        if($name != "Default.jpg" ){
            unlink($this->getParameter('images_directory').'/complements/'.$name);
        }
    }

}
