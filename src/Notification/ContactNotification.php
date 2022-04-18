<?php

namespace App\Notification;
use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

class ContactNotification extends AbstractController
{

     /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function notify(Contact $contact)
    {

        $message = (new \Swift_Message('Agence :'))
            ->setSubject('Subject' .$contact->getSubject())
            ->setFrom($contact->getEmail())
            ->setTo('contact@propriete.ma')
            ->setReplyTo($contact->getEmail())
            ->setBody($this->renderer->render('emails/emails.html.twig',[
                'contact' => $contact
            ]), 'text/html');
        $this->mailer->send($message);

    }
}