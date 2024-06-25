<?php

declare(strict_types=1);
/**
 * Comments controller.
 */

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Photos;
use App\Form\Type\CommentsType;
use App\Repository\CommentsRepository;
use App\Repository\PhotosRepository;
use App\Service\CommentsServiceInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommentsController.
 */
#[Route('/comments')]
class CommentsController extends AbstractController
{
    /**
     * Comments service.
     */
    private CommentsServiceInterface $commentsService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param CommentsServiceInterface $commentsService  CommentsServiceInteraface
     * @param TranslatorInterface      $translator       Translator
     * @param PhotosRepository         $photosRepository Photos Repository
     */
    public function __construct(CommentsServiceInterface $commentsService, TranslatorInterface $translator, PhotosRepository $photosRepository)
    {
        $this->commentsService = $commentsService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request            $request            HTTP Request
     * @param CommentsRepository $commentsRepository comments repository
     * @param PaginatorInterface $paginator          Paginator
     *
     * @return Response HTTP response
     */
    #[Route(name: 'comments_index', methods: 'GET')]
    public function index(Request $request, CommentsRepository $commentsRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $commentsRepository->findAll(),
            $request->query->getInt('page', 1),
            CommentsRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render('comments/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param CommentsRepository $commentsRepository Comments Repository
     * @param int                $id                 Id
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'comments_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    public function show(CommentsRepository $commentsRepository, int $id): Response
    {
        $comments = $commentsRepository->find($id);

        return $this->render(
            'comments/show.html.twig',
            ['comments' => $comments]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param Photos  $photos  Photos
     *
     * @return Response HTTP response
     */
    #[Route('/create/{id}', name: 'comments_create', requirements: ['id' => '[1-9]\d*'], methods: ['GET', 'POST'])]
    public function create(Request $request, Photos $photos): Response
    {
        $comments = new Comments();
        $user = $this->getUser();
        $comments->setEmail($user->getEmail());
        $comments->setNick($user->getNick());
        $comments->setPhoto($photos);
        $comments->setPostDate(new \DateTime('now'));

        $form = $this->createForm(
            CommentsType::class,
            $comments,
            ['action' => $this->generateUrl('comments_create', ['id' => $photos->getId()]), 'current_user' => $user, 'current_photos' => $photos]
        );
        // Blad
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentsService->save($comments);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('photos_show', ['id' => $photos->getId()]);
        }

        return $this->render('comments/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Delete.
     *
     * @param Request  $request  Request
     * @param Comments $comments Comments
     *
     * @return Response Response
     */
    #[Route('/delete/{id}', name: 'comments_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|POST')]
    public function delete(Request $request, Comments $comments): Response
    {
        $form = $this->createForm(
            FormType::class,
            $comments,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('comments_delete', ['id' => $comments->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentsService->delete($comments);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('comments_index');
        }

        return $this->render(
            'comments/delete.html.twig',
            [
                'form' => $form->createView(),
                'comments' => $comments,
            ]
        );
    }
}
