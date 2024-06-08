<?php
/**
 * Photos controller.
 */

namespace App\Controller;

use App\Entity\Photos;
use App\Service\PhotosServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
//use Symfony\Component\Routing\Attribute\Route; tego importu nie chcemy
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhotosController.
 */
#[Route('/photos')]
class PhotosController extends AbstractController
{

    /**
     * Constructor.
     *
     * @param PhotosServiceInterface $photosService Photos service
     * @param TranslatorInterface      $translator      Translator
     */
    public function __construct(private readonly PhotosServiceInterface $photosService)
    {
    }

    /**
     * Index action.
     *
     * @param int $page Page number
     * @return Response HTTP response
     */
    #[Route(name: 'photos_index', methods: 'GET')]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {
        $pagination = $this->photosService->getPaginatedList($page);
        return $this->render('photos/index.html.twig', ['pagination' => $pagination]);

    }

    /**
     * Show action.
     *
     * @param Photos $photos Photos
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'photos_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(int $id): Response
    {
        $photos = $this->getDoctrine()->getRepository(Photos::class)->find($id);
        return $this->render('photos/show.html.twig', ['photos' => $photos]);
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
        name: 'photos_create',
        methods: 'GET|POST',
    )]
    public function create(Request $request): Response
    {
        $photos = new Photos();
        $form = $this->createForm(PhotosType::class, $photos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->photosService->save($photos);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('photos_index');
        }

        return $this->render(
            'photos/create.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * Edit action.
     *
     * @param Request  $request  HTTP request
     * @param Photos $photos Photos entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'photos_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Photos $photos): Response
    {
        $form = $this->createForm(
            PhotosType::class,
            $photos,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('photos_edit', ['id' => $photos->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->photosService->save($photos);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('photos_index');
        }

        return $this->render(
            'photos/edit.html.twig',
            [
                'form' => $form->createView(),
                'photos' => $photos,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param Request  $request  HTTP request
     * @param Photos $photos Photos entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'photos_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Photos $photos): Response
    {
        $form = $this->createForm(FormType::class, $photos, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('photos_delete', ['id' => $photos->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->photosService->delete($photos);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('photos_index');
        }

        return $this->render(
            'photos/delete.html.twig',
            [
                'form' => $form->createView(),
                'photos' => $photos,
            ]
        );
    }
}