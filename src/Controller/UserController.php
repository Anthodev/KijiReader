<?php

namespace App\Controller;

use Exception;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Settings;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user")
 * @package App\Controller
 */
class UserController extends AbstractController
{

    private $userRepository;
    private $roleRepository;
    private $em;
    
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->em = $em;
    }

    /**
     * @Route("/new", methods={"POST"})
     * @param Request $request 
     * @return JsonResponse|void 
     */
    public function new(Request $request)
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

        $userEmail = $this->userRepository->findOneByEmail($email);
        $userUsername = $this->userRepository->findOneByUsername($username);

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
        $settings->setUser($user);
        $this->em->persist($settings);

        $user->setSettings($settings);

        $role = $this->getUserRole($roleUser);

        $user->setRole($role);

        try {
            $this->em->persist($user);
            $this->em->flush();

            return new JsonResponse($user, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }

    /**
     * @Route("/get/countUsers", methods={"GET"})
     * @param mixed $roleCode 
     * @return mixed|Role 
     */
    public function getUsersCount()
    {
        return new JsonResponse(count($this->userRepository->findAll()), 200);
    }

    public function getUserRole($roleCode)
    {
        $role = $this->roleRepository->findOneByName($roleCode);
        if (is_null($role)) {
            $role = new Role();

            if ($roleCode === 'User') {
                $role->setName('User');
                $role->setCode('ROLE_USER');
            } else {
                $role->setName('Admin');
                $role->setCode('ROLE_ADMIN');
            }

            $this->em->persist($role);
            $this->em->flush();
        }

        return $role;
    }

    /**
     * User login route
     * 
     * @Route("/login", name="login", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function login()
    {
        $user = $this->getUser();

        return new JsonResponse([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'role' => $user->getRole()
        ], 200);
    }
}
