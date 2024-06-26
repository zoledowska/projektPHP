<?php
/**
 * Upload controller.
 */

namespace App\Controller;

use App\Entity\Photos;
use Doctrine\ORM\EntityManagerInterface; // Use the correct manager interface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UploadController.
 */
#[Route('/upload')]
class UploadController extends AbstractController
{
    protected ?EntityManagerInterface $manager;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $manager Entity manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    // Uncomment and complete this method if needed
    // /**
    //  * Create action.
    //  *
    //  * @param Request $request HTTP request
    //  *
    //  * @return Response HTTP response
    //  */
    // #[Route(
    //     '/create',
    //     name: 'file_create',
    //     methods: 'GET|POST'
    // )]
    // public function create(Request $request): Response
    // {
    //     // Implement your file upload logic here
    //     // Save file on server
    //     // Generate photo record in DB
    //     // $photo = new Photos();
    //     // $form = $this->createForm(PhotosType::class, $photo);
    //     // $form->handleRequest($request);
    //     //
    //     // if ($form->isSubmitted() && $form->isValid()) {
    //     //     /** @var UploadedFile $file */
    //     //     $file = $form->get('file')->getData();
    //     //     // Save the file and update the database
    //     //
    //     //     $this->addFlash(
    //     //         'success',
    //     //         $this->translator->trans('message.created_successfully')
    //     //     );
    //     //
    //     //     return $this->redirectToRoute('file_index');
    //     // }
    //     //
    //     // return $this->render(
    //     //     'upload/create.html.twig',
    //     //     ['form' => $form->createView()]
    //     // );
    // }
} // Ensure this brace is on its own line
