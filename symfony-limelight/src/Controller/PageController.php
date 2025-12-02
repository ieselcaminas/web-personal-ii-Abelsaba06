<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Gallery;
use App\Form\CommentFormType;
use App\Repository\GalleryRepository;
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
    public function gallery(GalleryRepository $galeria): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $imagen=$galeria->findAll();
        return $this->render('page/gallery.html.twig', [
            'controller_name' => 'PageController',
            'images' => $imagen,
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
    #[Route('/imagen/{id?1}',name:'imagen')]
    public function mostrarImagen(ManagerRegistry $doctrine,Request $request,int $id): Response
    {
        $repositorio = $doctrine->getRepository(Gallery::class);
        $imagen=$repositorio->find($id);
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setGaleria($imagen);  
            $comment->setPublishedAt(new \DateTime());
            $imagen->setNumComments($imagen->getNumComments()+1);
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('imagen', ["id" => $imagen->getId()]);
        }
        return $this->render('page/imagen.html.twig', [
        'image' => $imagen,
        'commentForm' => $form->createView()
    ]);
    }
    #[Route('/imagen/{id?1}/commentarios',name:'comentarios')]
    public function mostrarCommnetarios(ManagerRegistry $doctrine,int $galeria_id): Response
    {
        $repositorio = $doctrine->getRepository(Comment::class);
        $comentarios=$repositorio->findBy(['galeria_id' => $galeria_id]);

        return $this->render('pages/listacomentarios.html.twig', [
        'comentarios' => $comentarios,]);
    }
}
