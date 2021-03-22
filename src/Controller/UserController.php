<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/api")
 */
class UserController extends AbstractController
{
    private $security;

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder,Security $security)
    {
        $this->passwordEncoder = $passwordEncoder;
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

     
    /**
     * @Route("/register", name="register", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $userName = $request->get('username');
        $FirstName = $request->request->get('firstname');
        $LastName = $request->request->get('lastname');
        $IdFacebook = $request->request->get('idfacebook');
        $Email = $request->request->get('email');
        $password = $request->request->get('password');

        $user = new User();
        $user->setUserName($userName);
        $user->setPassword($password);
        $user->setEmail($Email);
        $user->setFirstname($FirstName);
        $user->setLastname($LastName);
        $user->setIdfacebook($IdFacebook);

        $entityManager = $this->getDoctrine()->getManager();
            
        $plainpwd = $user->getPassword();
        $encoded = $this->passwordEncoder->encodePassword($user,$plainpwd);
        $user->setPassword($encoded);

        $entityManager->persist($user);
        $entityManager->flush();

        try{
            
        return new JsonResponse(['error' => false,"success"=>true]);
    }
    catch(Exception $e)
    {
        return new JsonResponse(['error' => true,"success"=>false,"Exception"=>$e]);
    }
    }

    /**
     * @Route("/connectedUser", name="user_show", methods={"GET"})
     */
    public function connectedUser(): JsonResponse
    {
        $user = $this->security->getUser();
        return new JsonResponse(['user' => $user->getUsername()]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): JsonResponse
    {
       /* $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user,$plainpwd);
            $user->setPassword($encoded);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);*/
        
        return new JsonResponse(['nom' => "search"]);
    }

    
 

}
