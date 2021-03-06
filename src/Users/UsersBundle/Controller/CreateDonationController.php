<?php

namespace Users\UsersBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Donations\DonationsBundle\Entity\Donations;
use Symfony\Component\HttpFoundation\Request;

class CreateDonationController extends Controller {

    public function CreateDonationAction(Request $request) {

        $success = false;
        //Setup a fresh $donation object
        $donation = new Donations();
        $donation->setDate(new \DateTime('now'));

        $form = $this->createFormBuilder($donation)
                ->add('category', TextType::class , array('label' => 'Catégorie'))
                ->add('title', TextType::class , array('label' => 'Titre'))
                ->add('description', TextareaType::class)
                ->add('city', TextType::class , array('label' => 'Lieux'))
                ->add('imageFile', VichImageType::class, array('label' => ' ', 'required' => false))
                ->add('save', SubmitType::class, array('label' => 'Envoyer' ,
                      'attr' =>  array('class' => 'btn btn-primary-outline waves-effect'),
                    ))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $donation->setAvailable(true);
            $donation->setCategory($form->get('category')->getData());
            $donation->setTitle($form->get('title')->getData());
            $donation->setDescription($form->get('description')->getData());
            $donation->setCity($form->get('city')->getData());

            $user = $this->get('security.token_storage')->getToken()->getUser();

            $donation->setUser($user);
            $user->addDonation($donation);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($user);
            $em->persist($donation);
            $em->flush();
            $success = true;
        }

        return $this->render('UsersUsersBundle:CreateDonation:create_donation.html.twig', array(
                    'form' => $form->createView(), 'success' => $success
        ));
    }

}
