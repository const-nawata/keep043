<?php

namespace Demos\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Demos\BlogBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller{
/**
* @Route("/hello/{name}")
* @Template()
*/
    public function indexAction($name){
        return array('name' => $name);
    }
//______________________________________________________________________________

/**
* @Route("/create")
*/
	public function createAction() {
		$post = new Post();
		$post->setTitle('Demo Blog');
		$post->setBody('Hello Symfony 2');
		$post->setCreatedDate(new \DateTime("now"));
		$post->setUpdatedDate(new \DateTime('now'));

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($post);
		$em->flush();

		return new Response('Created product id ' . $post->getId());
	}
//______________________________________________________________________________

/**
* @Route("/show/{id}")
*/
    public function showAction($id) {
        $post = $this->getDoctrine()->getRepository('DemosBlogBundle:Post')->find($id);

        if (!$post) {
            throw $this->createNotFoundException('Страница не найдена!');
        }

        $html =
<<<HTML
        <h1>{$post->getTitle()}</h1>

        <p>{$post->getBody()}</p>

        <hr/>
        <small>Запись создана {$post->getCreatedDate()->format("Y-m-d H:i:s")}</small>
HTML;

        return new Response($html);
    }
//______________________________________________________________________________

}//	Class end
