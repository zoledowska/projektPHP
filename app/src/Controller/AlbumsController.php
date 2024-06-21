<?php
/**
 * Albums controller.
 */

namespace App\Controller;

use App\Entity\Albums;
use App\Service\AlbumsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AlbumsController.
 */
#[Route('/album')]
class AlbumsController extends AbstractController
{
    private $translator;

    /**
     * Constructor.
     *
     * @param AlbumsServiceInterface $albumsService Albums service
     */
    public function __construct(private readonly AlbumsServiceInterface $albumsService)
    {

    }//end __construct()


    /**
     * Index action.
     *
     * @return Response HTTP response
     */
    #[Route(name: 'albums_index', methods: 'GET')]
    public function index(#[MapQueryParameter] int $page=1): Response
    {
        $pagination = $this->albumsService->getPaginatedList($page);

        return $this->render('albums/index.html.twig', ['pagination' => $pagination]);

    }//end index()


    /**
     * Show action.
     *
     * @param Request            $request            Request
     * @param AlbumsRepository   $AlbumsRepository   Albums Repository
     * @param PhotosService      $photosService      Photos Service
     * @param int                $id                 Id
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'albums_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Request $request, AlbumsRepository $albumsRepository, PhotosService $photosService, int $id): Response
    {
        $album = $albumsRepository->find($id);
        $pagination = $photosService->getPaginatedList($request->query->getInt('page', 1), $album);

        return $this->render('albums/show.html.twig', ['albums' => $album, 'pagination' => $pagination]);
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

    }//end create()


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
                $this->translator->trans('message.created_successfully')
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

    }//end edit()


    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Albums $albums Albums entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'albums_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Albums $albums): Response
    {
        if(!$this->albumsService->canBeDeleted($albums)) {
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


}//end class
