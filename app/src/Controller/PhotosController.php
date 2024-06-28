<?php
/**
 * Photos controller.
 */

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\Users;
use App\Repository\PhotosRepository;
use App\Form\Type\PhotosType;
use App\Service\CommentsService;
use App\Service\PhotosServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class PhotosController.
 *
 * Manages photo-related actions such as displaying, creating, editing, and deleting photos.
 */
#[Route('/photos')]
class PhotosController extends AbstractController
{
    /**
     * Photos service.
     */
    private PhotosServiceInterface $photosService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Photos repository.
     */
    private PhotosRepository $photosRepository;

    /**
     * Directory for storing photo files.
     */
    private string $photoFileDirectory;

    /**
     * Comments service.
     */
    private CommentsService $commentsService;

    /**
     * Constructor.
     *
     * @param PhotosServiceInterface $photosService      Photos service
     * @param TranslatorInterface    $translator         Translator
     * @param PhotosRepository       $photosRepository   Photos repository
     * @param string                 $photoFileDirectory Directory for storing photo files
     * @param CommentsService        $commentsService    Comments service
     */
    public function __construct(PhotosServiceInterface $photosService, TranslatorInterface $translator, PhotosRepository $photosRepository, string $photoFileDirectory, CommentsService $commentsService)
    {
        $this->photosService = $photosService;
        $this->translator = $translator;
        $this->photosRepository = $photosRepository;
        $this->photoFileDirectory = $photoFileDirectory;
        $this->commentsService = $commentsService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'photos_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->photosService->getPaginatedList($request->query->getInt('page', 1));

        return $this->render('photos/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Photos $photos Photos entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'photos_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Photos $photos): Response
    {
        $comments = $this->commentsService->getPaginatedListByPhotos(1, $photos);

        return $this->render('photos/show.html.twig', ['photos' => $photos, 'comments' => $comments]);
    }

    /**
     * Create action.
     *
     * @param Request          $request            HTTP request
     * @param string           $photoFileDirectory Directory for storing photo files
     * @param SluggerInterface $slugger            Slugger
     *
     * @return Response HTTP response
     */
    #[Route(
        '/create',
        name: 'photos_create',
        methods: 'GET|POST'
    )]
    public function create(Request $request, string $photoFileDirectory, SluggerInterface $slugger): Response
    {
        /** @var Users $users */
        $users = $this->getUser();
        $photos = new Photos();
        $photos->setAuthor($users);
        $photos->setUploadDate(new \DateTimeImmutable('now'));

        $form = $this->createForm(PhotosType::class, $photos);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            $file = $form->get('photoFile')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                $file->move($photoFileDirectory, $newFilename);
                $photos->setPhotoFilename($newFilename);

                $this->photosService->save($photos);
            }

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
     * @param Request          $request            HTTP request
     * @param Photos           $photos             Photos entity
     * @param string           $photoFileDirectory Directory for storing photo files
     * @param SluggerInterface $slugger            Slugger
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'photos_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Photos $photos, string $photoFileDirectory, SluggerInterface $slugger): Response
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
            $file = $form->get('photoFile')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                $file->move($photoFileDirectory, $newFilename);
                $photos->setPhotoFilename($newFilename);

                $this->photosService->save($photos);
            }

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
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
     * @param Request $request HTTP request
     * @param Photos  $photos  Photos entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'photos_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Photos $photos): Response
    {
        $form = $this->createForm(
            FormType::class,
            $photos,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('photos_delete', ['id' => $photos->getId()]),
            ]
        );
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
