<?php

namespace App\Controller\Frontend;

use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerYVQvw91\PaginatorInterface_82dac15;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    private SessionInterface $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        
    }
    /**
     * @Route("/", name="home")
     */
    public function index(BurgerRepository  $repoBurger, MenuRepository $repoMenu,PaginatorInterface $paginator,Request $request): Response
    {

        if ($this->getUser()) {
            if($this->getUser()->getRoles()[0] == "ROLE_GESTIONNAIRE"){
                return  $this->redirectToRoute("dashboard");
             }
        }
        
        $dataM = $repoMenu->findAll();
        $menus = $paginator->paginate(
            $dataM,
            $request->query->getInt('page',1),
            8
        );

        $dataB =  $repoBurger->findAll();
        $burgers = $paginator->paginate(
            $dataB,
            $request->query->getInt('page',1),
            8
        );
        
       // $menus=$repoMenu->findAll();

        
        return $this->render('frontend/home/index.html.twig',[


            'menus'=>$menus,
            'burgers'=>$burgers,
            'cart' =>$this->session->get('cart',[])

            ]);
    }

    /**
     * @Route("/menus", name="menus_show")
     */
    public function menus(MenuRepository $repoMenu, PaginatorInterface $paginator, Request $request): Response
    {
        
        $data = $repoMenu->findAll();
        $menus = $paginator->paginate(
            $data,
            $request->query->getInt('page',1),
            10
        );

        //dd($menus);


        return $this->render('frontend/shop/menu.html.twig',[

            'menus'=>$menus,
            'cart' =>$this->session->get('cart',[])
            

            ]);
    }

    

    /**
     * @Route("/burgers", name="burgers_show")
     */
    public function burgers(BurgerRepository $repoBurger): Response
    {
        
        $burgers=$repoBurger->findAll();


        return $this->render('frontend/shop/burger.html.twig',[

            'burgers'=>$burgers,
            'cart' =>$this->session->get('cart',[])
            

            ]);
    }
}
