<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\GalleryFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admin/images', name: 'app_images')]
    public function images(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $gallery = new Gallery();
        $form = $this->createForm(GalleryFormType::class, $gallery);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                try {
                    $file->move($this->getParameter('images_directory'), $newFilename);
                    $filesystem = new Filesystem();
                    $filesystem->copy(
                        $this->getParameter('images_directory') . '/' . $newFilename,
                        $this->getParameter('portfolio_directory') . '/' . $newFilename,
                        true
                    );
                } catch (FileException $e) {
                    return new Response("Error al subir el archivo: " . $e->getMessage(), 500);
                }
                $gallery->setFile($newFilename);
            }
            $gallery = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($gallery);
            $entityManager->flush();
        }

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
     #[Route('/admin/images/list', name: 'app_images_list')]
      public function lista(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Gallery::class);
        $images = $repository->findAll(); // obtiene todas las imágenes

        return $this->render('admin/imageslist.html.twig', [
        'images' => $images,
    ]);
    }
}
