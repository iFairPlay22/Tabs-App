<?php

namespace App\Form;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

abstract class FormUtils
{
    public static function updateDBIfValid(Request $request, FormInterface $form, ObjectManager $objectManager)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $objectManager->persist($form->getData());
            $objectManager->flush();

            return true;
        }

        return false;
    }
}
