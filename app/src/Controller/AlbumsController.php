<?php
/**
 * Albums controller.
 */

namespace App\Controller;

use App\Entity\Albums;
use App\Form\Type\AlbumsType;
use App\Service\AlbumsService;
use App\Service\PhotosService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AlbumsController.
 */
#[Route('/album')]
class AlbumsController extends AbstractController
{
    private $translator;

    private PhotosService $photosService;

    private AlbumsService $albumsService;

    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator Translator
     */
    public function __construct(TranslatorInterface $translator, PhotosService $photosService, AlbumsService $albumsService)
    {
        $this->translator = $translator;
        $this->photosService = $photosService;
        $this->albumsService = $albumsService;
    }// end __construct()

    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route(name: 'albums_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->albumsService->getPaginatedList($request->query->getInt('page', 1));

        return $this->render('albums/index.html.twig', ['pagination' => $pagination]);
    }// end index()

    /**
     * Show action.
     *
     * @param Request $request Request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'albums_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Request $request, Albums $albums): Response
    {
        $pagination = $this->photosService->getPhotosByAlbum($request->query->getInt('page', 1), $albums);

        return $this->render('albums/show.html.twig', ['albums' => $albums, 'pagination' => $pagination]);
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'albums_create',
        methods: 'GET|POST',
    )]
    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'albums_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $album = new Albums();
        $form   = $this->createForm(AlbumsType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->albumsService->save($album);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('albums_index');
        }

        return $this->render(
            'albums/create.html.twig',
            ['form' => $form->createView()]
        );
    }// end create()

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Albums  $albums  Albums entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'albums_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Albums $albums): Response
    {
        $form = $this->createForm(
            AlbumsType::class,
            $albums,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('albums_edit', ['id' => $albums->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->albumsService->save($albums);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('albums_index');
        }

        return $this->render(
            'albums/edit.html.twig',
            [
                'form'   => $form->createView(),
                'albums' => $albums,
            ]
        );
    }// end edit()

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Albums  $albums  Albums entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'albums_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Albums $albums): Response
    {

        if (!$this->albumsService->canBeDeleted($albums)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.albums_contains_tasks')
            );

            return $this->redirectToRoute('albums_index');
        }

        $form = $this->createForm(
            FormType::class,
            $albums,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('albums_delete', ['id' => $albums->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->albumsService->delete($albums);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('albums_index');
        }

        return $this->render(
            'albums/delete.html.twig',
            [
                'form' => $form->createView(),
                'albums' => $albums,
            ]
        );
    }
}// end class
