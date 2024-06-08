<?php
/**
 * Avatar controller.
 */

namespace App\Controller;

use App\Entity\Avatar;
use App\Entity\Photos;
use App\Entity\User;
use App\Form\Type\AvatarType;
use App\Service\AvatarServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AvatarController.
 */
#[Route('/upload')]
class UploadController extends AbstractController
{
    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    protected ?ObjectManager $manager;

//    #[Route(
//        '/create',
//        name: 'file_create',
//        methods: 'GET|POST'
//    )]
//    public function create(Request $request): Response
//    {

    // Save file on server
    // Generate photo record in DB
//    $photo = new Photos();
//    $form = $this->createForm(Photos::class, $photo);
//    $form->handleRequest($request);
//    $form->get("nazwa inputu");
//    $form->get("sfd");

//    $photo->save();

    // file -> saveOnDrive()


//        if ($form->isSubmitted() && $form->isValid()) {
//            /** @var UploadedFile $file */
//            $file = $form->get('file')->getData();
//            /$this->avatarService->create(
//                $file,
//                $avatar,
//                $user
//            );
//
//            $this->addFlash(
//                'success',
//                $this->translator->trans('message.created_successfully')
//            );
//
//            return $this->redirectToRoute('task_index');
//        }
//
//        return $this->render(
//            'avatar/create.html.twig',
//            ['form' => $form->createView()]
//        );

//    return
//    }

}
