<?php

namespace App\Controller;

use Exception;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Settings;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user")
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
 * @package App\Controller
 */
class UserController extends AbstractController
{

    private $userRepository;
    private $roleRepository;
    private $em;
    private $serializer;
    
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->em = $em;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/new", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
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

        $checkUserEmail = $this->userRepository->findOneByEmail($email);
        $checkUserUsername = $this->userRepository->findOneByUsername($username);

        if (!is_null($checkUserEmail)) {
            return new JsonResponse([
                'message' => 'Email already taken.'
            ], 409);
        } else if (!is_null($checkUserUsername)) {
            return new JsonResponse([
                'message' => 'Username already taken.'
            ], 409);
        }

        $role = $this->getUserRole($roleUser);

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);

        $settings = new Settings();
        $settings->setUser($user);
        $this->em->persist($settings);

        $user->setSettings($settings);

        $user->setRole($role);
        $role->addUser($user);

        try {
            $this->em->persist($user);
            $this->em->flush();

            return new JsonResponse($user, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }

    /**
     * @Route("/countUsers", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function getUsersCount()
    {
        return new JsonResponse(count($this->userRepository->findAll()), 200);
    }

    /**
     * 
     * @param mixed $roleCode 
     * @return mixed|Role 
     */
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
            'role' => $user->getRole()->getName()
        ], 200);
    }

    /**
     * Return user info
     * 
     * @Route("/profile", methods={"GET"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function getUserInfo()
    {
        $user = $this->getUser();

        $serializedUser = $this->serializer->serialize($user, 'json');

        $response = new Response($serializedUser);
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/set_read_display", methods={"POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function setDisplayRead()
    {
        $user = $this->getUser();

        try {
            $readStatus = $user->getSettings()->getDisplayUnread();
            $readStatus = !$readStatus;

            $user->getSettings()->setDisplayUnread($readStatus);

            $this->em->flush();

            return new JsonResponse('OK', 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }
}
