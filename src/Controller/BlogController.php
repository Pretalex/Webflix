<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/articles", name="blog")
     */
    public function blog(): Response
    {
        return $this->render('blog/blog.html.twig', [

        ]);
    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function article($id): Response
    {

        return $this->render('blog/article.html.twig', [
            'id' => $id
        ]);
    }
}
