<?php
/**
 * Created by PhpStorm.
 * User: melpe
 * Date: 06/01/2018
 * Time: 12:03
 */

namespace MP\PlatformBundle\Controller;



use MP\PlatformBundle\Entity\Advert;
use MP\PlatformBundle\Form\AdvertEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdvertController extends Controller
{

    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('MPPlatformBundle:Advert')
            ->findAll()
        ;

        return $this->render('MPPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
        ));
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('MPPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
        }

        $listApplications = $em
            ->getRepository('MPPlatformBundle:Application')
            ->findBy(array('advert' => $advert))
        ;

        return $this->render('MPPlatformBundle:Advert:view.html.twig', array(
            'advert'           => $advert,
            'listApplications' => $listApplications
        ));
    }

    public function addAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès limité à l\' admin.');
        }
        $advert = new Advert();

            $form = $this->get('form.factory')->createBuilder(FormType::class, $advert)
            ->add('date',      DateType::class)
            ->add('title',     TextType::class)
            ->add('content',   TextareaType::class)
            ->add('author',    TextType::class)
            ->add('published', CheckboxType::class, array('required' => true))
            ->add('save',      SubmitType::class)
            ->getForm()
        ;

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Article bien enregistrée.');

                return $this->redirectToRoute('mp_platform_view', array('id' => $advert->getId()));
            }
        }

        return $this->render('MPPlatformBundle:Advert:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction(Request $request, $id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès limité à l\' admin.');
        }
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('MPPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'article a bien été supprimée.");

            return $this->redirectToRoute('mp_platform_home');
        }

        return $this->render('MPPlatformBundle:Advert:delete.html.twig', array(
            'advert' => $advert,
            'form'   => $form->createView(),
        ));
    }

    public function menuAction($limit)
    {
        $em = $this->getDoctrine()->getManager();

        $listAdverts = $em->getRepository('MPPlatformBundle:Advert')->findBy(
            array(),
            array('date' => 'desc'),
            $limit,
            0
        );

        return $this->render('MPPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

    public function editAction($id, Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Accès limité à l\' admin.');
        }
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('MPPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'article d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Article bien modifiée.');

            return $this->redirectToRoute('mp_platform_view', array('id' => $advert->getId()));
        }

        return $this->render('MPPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert,
            'form'   => $form->createView(),
        ));
    }
    public function cvAction()
    {
        return $this->render('MPPlatformBundle:Advert:cv.html.twig');
    }
}
