<?php
namespace BlogBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class DefaultController extends Controller
{
  /**
   * @Route("/", name="home")
   */
  public function indexAction(Request $request)
  {
      $em = $this->get('doctrine.orm.entity_manager');
      $dql = "SELECT p FROM BlogBundle:Post p ORDER BY p.createdAt DESC";
      $query = $em->createQuery($dql);

      $paginator = $this->get('knp_paginator');
      $pagination = $paginator->paginate(
          $query,
          $request->query->getInt('page', 1),
          5
      );

      $categories = $this->getAllCategories();
      return $this->render('BlogBundle:Default:index.html.twig', array("categories" => $categories, 'pagination' => $pagination));
  }

  /**
   * @Route("/show/{title}", name="show_article")
   */
  public function showArticleAction(Request $request, $title)
  {
      $em = $this->getDoctrine()->getEntityManager();
      $repository = $em->getRepository('BlogBundle:Post');
      $post = $repository->findOneBy(array("title" => $title));

      $comment = new Comment();
      $comment->setAuthor($this->getUser());
      $comment->setPosts($post);
      $comment->setDate(new \DateTime());

      $form = $this->createFormBuilder($comment)
          ->add('content', TextType::class, array('label' => 'Contenu : ', 'data' => ''))
          ->add('save', SubmitType::class, array('label' => 'Commenter'))
          ->getForm();

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
          $comment = $form->getData();
          $em->persist($comment);
          $em->flush();
      }

      $categories = $this->getAllCategories();
      return $this->render('BlogBundle:Default:display.html.twig', array("post" => $post, "form" => $form->createView(), "categories" => $categories));
  }


  private function getAllCategories()
  {
      $em = $this->getDoctrine()->getEntityManager();
      $repository = $em->getRepository('BlogBundle:Category');
      $categories = $repository->findAll();
      return $categories;
  }
}
