<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{

    private SessionInterface $session;
    private UserPasswordHasherInterface $encoder;
    public function __construct(SessionInterface $session,UserPasswordHasherInterface $encoder)
    {
        $this->session = $session;
        $this->encoder = $encoder;
        
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(HttpRequest $request, EntityManagerInterface $em,ValidatorInterface $validator): Response
    {
        if ($this->getUser()) {
            dd($this->getUser());
           return $this->redirectToRoute('home');
        }
        
        if($request->isMethod('post')){

            $client = new Client();
            $user= new User();
            
            $client->setNom($request->request->get('nom'));
            $client->setPrenom($request->request->get('prenom'));
            $client->setEmail($request->request->get('email'));
            $tel= "+221".$request->request->get('telephone');
            //dd($tel);
            $client->setTelephone($tel);
            $client->setPassword($this->encoder->hashPassword($client, $request->request->get('password')));
            $user->setNom($request->request->get('nom'))
            ->setPrenom($request->request->get('prenom'))
            ->setEmail($request->request->get('email'))
            ->setPassword($this->encoder->hashPassword($client, $request->request->get('password')));
            $errors = $validator->validate($user);
            $error=$validator->validate($client);
            if (count($errors) > 0 || count($error) > 0 ) {
                dd($errors);

                $this->addFlash(
                    'error_message',
                    'Veuillez verifier votre saissie'
                );
                return $this->redirectToRoute('app_register');
            }
            
            
            //dd("");
            $em->persist($client);
            $em->flush();
            return $this->redirectToRoute('app_login');
        }
        

        return $this->render('security/register.html.twig', [
            
            'cart' =>$this->session->get('cart',[])
        
        ]);
    }
    
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('home');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername, 
            'error' => $error,
            'cart' =>$this->session->get('cart',[])]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
