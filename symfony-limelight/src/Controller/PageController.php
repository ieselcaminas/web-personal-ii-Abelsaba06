<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('page/about.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/contact', name: 'contact')]
    public function contacto(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('page/contact.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/gallery', name: 'gallery')]
    public function gallery(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('page/gallery.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/testimonial', name: 'testimonial')]
    public function testimonial(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('page/testimonial.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
    #[Route('/service', name: 'service')]
    public function service(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('page/service.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
