<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Settings;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/new", methods={"POST"})
     * @param Request $request 
     * @return JsonResponse|void 
     */
    public function new(Request $request, UserRepository $userRepository, RoleRepository $roleRepository, EntityManager $em)
    {
        $username = '';
        $email = '';
        $password = '';

        $data = $request->getContent();

        if (!empty($data)) {
            $decodedData = \json_decode($data, true);

            $username = $decodedData['username'];
            $email = $decodedData['email'];
            $password = $decodedData['password'];
        }

        $user = $userRepository->findOneByEmail($email);

        if (!is_null($user)) {
            return new JsonResponse([
                'message' => 'Email already exists'
            ], 409);
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);

        $settings = new Settings();
        $user->setSettings($settings);

        $role = $roleRepository->findOneByCode("ROLE_USER");
        if(is_null($role)) {
            $role = new Role();
            $role->setName('User');
            $role->setCode('ROLE_USER');

            $em->persist($role);
            $em->flush();
        }

        $user->setRole($role);

        try {
            $em->persist($user);
            $em->flush();

            return new JsonResponse($user, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }
}
