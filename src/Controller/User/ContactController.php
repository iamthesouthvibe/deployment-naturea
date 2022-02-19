<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\EmailModel;
use App\Services\EmailSender;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{
    
    /**
     * @Route("/new", name="contact_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,
                       EmailSender $emailSender): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getdoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            //envoi d'un message par mail  
            $user = (new User())
                ->setEmail('fehlenlili64100@gmail.com')
                ->setFirstname('Nelly')
                ->setLastname('Felin');
            $email = (new EmailModel())
                ->setTitle("Bonjour  ".$user->getLastname() ." ".$user->getFirstname())
                ->setSubject("Nouveau contact sur votre site")
                ->setContent("<br>De : ".$contact->getEmail()
                            ."<br>Nom : ".$contact->getLastname()
                            ."<br>Prénom : ".$contact->getFirstname()
                            ."<br>Sujet : ".$contact->getSujet()
                            ."<br>Message : ".$contact->getContent());

            $emailSender->sendEmailContactByMailJet($user, $email);


            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
            $this->addFlash('contact_success', 'Votre message a bien été envoyé. Un administrateur vous répondra trés rapidement !');          
        }
        if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('contact_cancel', 'Votre message ne peut pas être envoyé. Veuillez recommencer !');          
        }
        return $this->render('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    
}
