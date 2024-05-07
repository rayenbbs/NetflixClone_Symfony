<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $address = $data['email'];
            $subject = $data['name'];
            $message = $data['message'];
            $transport = Transport::fromDsn('smtp://ramykaabi7@gmail.com:faefmqcxinhhhysu@smtp.gmail.com:587');
            $mailer = new Mailer($transport);
            $email = (new Email())
                ->from($address)
                ->to('tuniflixcontact@gmail.com')
                ->subject('New message from ' . $subject)
                ->text('Sender email: ' . $address . ' Message: ' . $message);
            try {
                $mailer->send($email);
                $this->addFlash('success', 'Your message has been sent successfully!');
            } catch (\Exception $e) {
                $this->addFlash('error', 'There was an error sending your message. Please try again later.');
            }
        }


        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView()
        ]);
    }
}
