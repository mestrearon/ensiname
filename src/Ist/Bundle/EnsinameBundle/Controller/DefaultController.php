<?php

namespace Ist\Bundle\EnsinameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl(
            $this->get('security.context')->isGranted('ROLE_ADMIN') ?
                'dashboard' : 'login'));
    }

    /**
     * @Route("/dashboard", name="dashboard")
     * @Template()
     */
    public function dashboardAction()
    {

        if (isset($_SERVER['HTTP_REFERER']) && substr($_SERVER['HTTP_REFERER'], -5) == 'login')
            $this->get('session')->getFlashBag()->add('success', 'bem vindo, admin!');

        return array();
    }
}
