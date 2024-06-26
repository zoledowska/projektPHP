<?php
/**
 * Users controller.
 */

namespace App\Controller;

use App\Form\Type\UsersType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Service\UsersServiceInterface;
use App\Service\PhotosService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UsersController.
 */
#[Route('/user')]
class UsersController extends AbstractController
{
    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Users service.
     */
    private UsersServiceInterface $usersService;

    /**
     * Constructor.
     *
     * @param TranslatorInterface   $translator   Translator
     * @param UsersServiceInterface $usersService Users service
     */
    public function __construct(TranslatorInterface $translator, UsersServiceInterface $usersService)
    {
        $this->translator = $translator;
        $this->usersService = $usersService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'users_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->usersService->getPaginatedList($request->query->getInt('page', 1));

        return $this->render('users/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Request         $request         HTTP request
     * @param UsersRepository $usersRepository Users repository
     * @param PhotosService   $photosService   Photos Service
     * @param int             $id              User ID
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'users_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Request $request, UsersRepository $usersRepository, PhotosService $photosService, int $id): Response
    {
        $user = $usersRepository->find($id);
        $pagination = $photosService->getPaginatedList($request->query->getInt('page', 1), $user);

        return $this->render('users/show.html.twig', ['users' => $user, 'pagination' => $pagination]);
    }

    /**
     * Create action.
     *
     * @param Request                     $request            HTTP request
     * @param UserPasswordHasherInterface $userPasswordHasher Password hasher
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'users_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $this->usersService->save($user);
            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('users_index');
        }

        return $this->render(
            'users/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Users   $users   Users entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'users_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Users $users): Response
    {
        $form = $this->createForm(
            UsersType::class,
            $users,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('users_edit', ['id' => $users->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->usersService->save($users);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('users_index');
        }

        return $this->render(
            'users/edit.html.twig',
            [
                'form' => $form->createView(),
                'users' => $users,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Users   $users   Users entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'users_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Users $users): Response
    {
        if (!$this->usersService->canBeDeleted($users)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.user_has_photos')
            );

            return $this->redirectToRoute('users_index');
        }

        $form = $this->createForm(
            FormType::class,
            $users,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('users_delete', ['id' => $users->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->usersService->delete($users);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('users_index');
        }

        return $this->render(
            'users/delete.html.twig',
            [
                'form' => $form->createView(),
                'users' => $users,
            ]
        );
    }
}
