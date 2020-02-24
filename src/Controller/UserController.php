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
        $roleUser = '';

        $data = $request->getContent();

        if (!empty($data)) {
            $decodedData = \json_decode($data, true);

            $username = $decodedData['username'];
            $email = $decodedData['email'];
            $password = $decodedData['password'];
            $roleUser = $decodedData['roleUser'];
        }

        $userEmail = $userRepository->findOneByEmail($email);
        $userUsername = $userRepository->findOneByUsername($username);

        if (!is_null($userEmail)) {
            return new JsonResponse([
                'message' => 'Email already taken.'
            ], 409);
        } else if (!is_null($userUsername)) {
            return new JsonResponse([
                'message' => 'Username already taken.'
            ], 409);
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);

        $settings = new Settings();
        $user->setSettings($settings);

        $role = $this->getUserRole($roleUser, $roleRepository, $em);

        $user->setRole($role);

        try {
            $em->persist($user);
            $em->flush();

            return new JsonResponse($user, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }

    public function getUserRole($roleCode, $roleRepository, $em)
    {
        $role = $roleRepository->findOneByCode($roleCode);
        if (is_null($role)) {
            $role = new Role();

            if ($roleCode === 'ROLE_USER') {
                $role->setName('User');
                $role->setCode('ROLE_USER');
            } else {
                $role->setName('Admin');
                $role->setCode('ROLE_ADMIN');
            }

            $em->persist($role);
            $em->flush();
        }

        return $role;
    }
}
