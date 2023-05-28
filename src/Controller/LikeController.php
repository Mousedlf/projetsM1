<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Project;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/{id}', name: 'app_like')]
    public function index(Project $project, LikeRepository $repository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if($project->isLikedBy($user)){

            $like = $repository->findOneBy([
                'author'=>$user,
                'project'=>$project
            ]);
            $manager->remove($like);
            $isLiked = false;

        }else{
            $like = new Like();
            $like->setProject($project);
            $like->setAuthor($user);
            $manager->persist($like);
            $isLiked = true;
        }
        $manager->flush();

        $data = [
            'liked'=>$isLiked,
            'count'=>$repository->count(['project'=>$project])
        ];


        return $this->json($data, 200);
    }
}
