<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactType;
use App\DTO\ContactDTO;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Header\Headers;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(MailerInterface $mailer, Request $request, ServiceRepository $serviceRepository): Response
    {
        $contact = new ContactDTO();
        $contactForm = $this->createForm(ContactType::class, $contact);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact->setIpAddress($request->getClientIp());
            $service = $contact->getService();

            try {
                $email = (new TemplatedEmail())
                    ->from($contact->getEmail())
                    ->to($service->getEmail() ?? 'generic@monsite.com')
                    ->subject('Nouveau message ' .$service->getName() .' de la part de ' .$contact->getName())
                    ->text($contact->getMessage())
                    ->htmlTemplate('emails/contact.html.twig')
                    ->locale('fr')
                    ->context([
                        'contact' => $contact
                ]);
                $email->getHeaders()->addTextHeader('X-ip-address', $contact->getIpAddress());
            
            
                $mailer->send($email);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible d\'envoyer votre email.');
            }
            
            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $contactForm
        ]);
    }
}
