<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\TeamMember;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/project')]

class ProjectController extends AbstractController
{
    #[Route('/', name: 'app_project')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects=$projectRepository->findAll();

        return $this->render('project/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}', name: 'show_project')]
    public function show(Project $project): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $project,
        ]);
    }


    #[Route('/new', name: 'new_project', priority: 2)]
    #[Route('/edit/{id}', name: 'edit_project', priority: 2)]
    public function create(Request $request, EntityManagerInterface $manager,Project $project=null): Response
    {
        $routeName = $request->attributes->get('_route');


        $title = "create";

       if($routeName === "edit_project"){
           $title= "edit";
       }

        if($routeName === "new_project"){
            $project= new Project();
        }

        $projectForm=$this->createForm(ProjectType::class, $project);
        $projectForm->handleRequest($request);

/*      if($this->getUser()->getId()== $projectRepositor ){

            $this->addFlash(
                'notice',
                'You can only have one project. Please edit the one you already created.'
            );
            return $this->redirectToRoute('app_project');
        }*/
        if($projectForm->isSubmitted() && $projectForm->isValid()){
            $project->setAuthor($this->getUser());

            $teamMembers=$projectForm->getData()->getTeamMembers();
            foreach ($teamMembers as $teamMember){
                $addedTeamMember = new TeamMember();
                $addedTeamMember->setName($teamMember->getName());
                $addedTeamMember->setProject($project);
            }

            $images=$projectForm->getData()->getImages();
            foreach($images as $image){
                $image->setProject($project);
            }

            $manager->persist($project);
            $manager->flush();

            return $this->redirectToRoute('app_project');
        }

        return $this->renderForm('project/new.html.twig', [
            'projectForm' => $projectForm,
            'title'=>$title
        ]);
    }

//    #[Route('/delete/{id}', name: 'delete_project')]
//    public function delete(Project $project, EntityManagerInterface $manager): Response
//    {
//        if($project){
//            $manager->remove($project);
//            $manager->flush();
//        }
//
//        return $this->redirectToRoute('app_project');
//    }

    #[Route('/delete/{id}', name: 'delete_project', methods: ['POST'])]
    public function delete(Request $request, Project $project, ProjectRepository $projectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $projectRepository->remove($project, true);
        }

        return $this->redirectToRoute('app_project', [], Response::HTTP_SEE_OTHER);
    }


}
