<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $users = Zend_Registry::get('doctrine')
                    ->getDocumentManager()
                    ->getRepository('App\Document\User')
                    ->findAll();

        $this->view->users = $users;
    }

    public function newAction()
    {
        $user = new App\Document\User();
        $user->firstname = 'Chad';
        $user->lastname = 'Ladensack';
        $user->username = 'abarak';
        $user->password = 'test1test';

        Zend_Registry::get('doctrine')->getDocumentManager()->persist($user);
        Zend_Registry::get('doctrine')->getDocumentManager()->flush();

        $this->view->user = $user;
    }
}
