<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Project;
use App\Form\ImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/image')]

class ImageController extends AbstractController
{
    #[Route('/{id}', name: 'app_image')]
    public function index(Project $project): Response
    {
        $image = new Image();
        $imageForm=$this->createForm(ImageType::class, $image);

        return $this->render('image/index.html.twig', [
            'project'=>$project,
            'imageForm'=>$imageForm
        ]);
    }

    #[Route('/add/{id}', name: 'add_image')]
    public function addImage(Project $project, Request $request, EntityManagerInterface $manager): Response
    {
        $image = new Image();
        $imageForm=$this->createForm(ImageType::class, $image);
        $imageForm->handleRequest($request);
        if($imageForm->isSubmitted() && $imageForm->isValid()){

            $image->setProject($project);
            $manager->persist($image);
            $manager->flush();
        }

        return $this->redirectToRoute('app_image', [
            'id' => $project->getId()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_image')]
    public function delete(Image $image, EntityManagerInterface $manager): Response
    {
        if($image){
            $project=$image->getProject();
            $manager->remove($image);
            $manager->flush();
        }

        return $this->redirectToRoute('app_image', [
            'id' => $project->getId()
        ]);
    }

}
