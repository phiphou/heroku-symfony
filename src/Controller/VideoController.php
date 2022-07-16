<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $em, VideoRepository $videoRepository): Response
    {
        $video = new Video();

        $form = $this->createForm(VideoType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video = $form->getData();

            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('video/index.html.twig', [
            'form' => $form->createView(),
            'videos' => $videoRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_video')]
    public function video(Video $video): Response
    {
        return $this->render('video/video.html.twig', [
            'title' => $video->getTitle(),
            'url' => $video->getUrl(),
        ]);
    }
}
