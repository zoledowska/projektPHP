<?php
/**
 * PhotoFile controller.
 */

namespace App\Controller;

use App\Entity\PhotoFile;
use App\Entity\Photos;
use App\Form\Type\PhotoFileType;
use App\Service\PhotoFileServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class PhotoFileController.
 */
#[Route('/photoFile')]
class PhotoFileController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param PhotoFileServiceInterface $photoFileService PhotoFile service
     * @param TranslatorInterface       $translator       Translator
     */
    public function __construct(private readonly PhotoFileServiceInterface $photoFileService, private readonly TranslatorInterface $translator)
    {
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
        name: 'photoFile_create',
        methods: 'GET|POST'
    )]
    public function create(Request $request): Response
    {
        /** @var Photos $photos */
        $photos = $this->getPhotos();
        if ($photos->getPhotoFile()) {
            return $this->redirectToRoute(
                'photoFile_edit',
                ['id' => $photos->getId()]
            );
        }

        $photoFile = new PhotoFile();
        $form = $this->createForm(
            PhotoFileType::class,
            $photoFile,
            ['action' => $this->generateUrl('photoFile_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->photoFileService->create(
                $file,
                $photoFile,
                $photos
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('task_index');
        }

        return $this->render(
            'photoFile/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param Request   $request   HTTP request
     * @param PhotoFile $photoFile PhotoFile entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}/edit',
        name: 'photoFile_edit',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|PUT'
    )]
    public function edit(Request $request, PhotoFile $photoFile): Response
    {
        /** @var Photos $user */
        $user = $this->getPhotos();
        if (!$user->getPhotoFile()) {
            return $this->redirectToRoute('photoFile_create');
        }

        $form = $this->createForm(
            PhotoFileType::class,
            $photoFile,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('photoFile_edit', ['id' => $photoFile->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();
            $this->photoFileService->update(
                $file,
                $photoFile,
                $user
            );

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('task_index');
        }

        return $this->render(
            'photoFile/edit.html.twig',
            [
                'form' => $form->createView(),
                'photoFile' => $photoFile,
            ]
        );
    }
}
