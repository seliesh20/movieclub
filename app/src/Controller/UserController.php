<?php

namespace App\Controller;

use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


use App\Entity\User;
use App\Repository\UserRepository;


class UserController extends AbstractController
{
    #[Route('/api/register', name: 'user_register', methods:["post"])]
    public function index(Request $request, UserRepository $userRepository, ValidatorInterface $validator): Response
    {
        $name = trim($request->get('name'));
        $email = trim($request->get('email'));
        $password = trim($request->get('password'));
        
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        $errors = $validator->validate($user);
        if(count($errors)){
            $message = [];
            foreach($errors as $error){
                $message[$error->getPropertyPath()][] = $error->getMessage();
            }   
            return new JsonResponse(['status'=>'failure', 'data'=>['errors' => $message]]);
        }
        
        //save
        $userRepository->save($user);
        return new JsonResponse(['status'=>'success', 'data'=>['email'=>$email]]);
    }
}
