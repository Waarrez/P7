<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly RouterInterface $router,
        private readonly TagAwareCacheInterface $cache
    )
    {}

    #[Route('/users', name: 'get_users_collection', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $this->getUser();

        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        // Récupérer les utilisateurs associés au client
        $users = $this->userRepository->findUsersByCustomer($customer);

        /** @var array<array-key, mixed> $users */
        $users = $this->serializer->normalize($users, 'json', [
            AbstractNormalizer::GROUPS => ['users:read']
        ]);

        return new JsonResponse($users, Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'get_user', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $this->getUser();

        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        if ($user->getCustomer() !== $customer) {
            return new JsonResponse(['error' => 'Access denied'], Response::HTTP_FORBIDDEN);
        }

        $normalizedUser = $this->serializer->normalize($user, 'json', [
            AbstractNormalizer::GROUPS => ['users:read']
        ]);

        return new JsonResponse($normalizedUser, Response::HTTP_OK);
    }

    #[Route('/users', name: 'post_users', methods: [Request::METHOD_POST])]
    public function create(Request $request): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $this->getUser();

        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();

        $data['firstName'] = $data['first_name'] ?? null;
        $data['lastName'] = $data['last_name'] ?? null;
        $data['phoneNumber'] = $data['phone_number'] ?? null;

        /** @var User $user */
        $user = $this->serializer->denormalize($data, User::class, null, [
            AbstractNormalizer::ATTRIBUTES => ['firstName', 'lastName', 'email', 'phoneNumber']
        ]);

        $user->setCustomer($customer);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $this->userRepository->add($user, true);

        $link = $this->router->generate('get_user', ['id' => $user->getId()]);

        return new JsonResponse(['message' => 'User created successfully', 'link' => $link], Response::HTTP_CREATED);
    }

    /**
     * @throws InvalidArgumentException|\Psr\Cache\InvalidArgumentException
     */
    #[Route('/users/{id}', name: 'delete_users', methods: [Request::METHOD_DELETE])]
    public function delete(string $id, Request $request): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $this->getUser();

        if (!$customer) {
            return new JsonResponse(['error' => 'Customer not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $user = $this->userRepository->findUserWithIdAndCustomer($id, $customer, false);

            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
            }

            $this->userRepository->remove($user, true);

            $this->cache->invalidateTags(['usersCache']);

            return new JsonResponse(['message' => 'L\'utilisateur a bien été supprimé'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
