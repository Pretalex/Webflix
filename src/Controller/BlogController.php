<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/gestion-blog/nouvel-article", name="blog_article_new")
     */
    public function newBlogArticle(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                $this->addFlash('success', "L'article a bien été enregistré.");
                return $this->redirectToRoute('blog_article_new');
            } else {
                $this->addFlash('danger', "Le formulaire comporte des erreurs.");
            }
        }

        return $this->render('blog/new_article.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
