<?php
/**
 * Created by PhpStorm.
 * User: melpe
 * Date: 06/01/2018
 * Time: 12:03
 */

namespace MP\PlatformBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller
{
    public function indexAction()
    {
        $content = $this->get('templating')->render('MPPlatformBundle:Advert:index.html.twig');

        return new Response ($content);
    }
}