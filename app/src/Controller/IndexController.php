<?php
/**
 * Index controller.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller for handling the root route.
 */
#[Route('/')]
class IndexController extends AbstractController
{
    /**
     * Redirects the root URL to the photos index page.
     *
     * @return RedirectResponse a redirection response to the photos index route
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('photos_index');
    }
}
