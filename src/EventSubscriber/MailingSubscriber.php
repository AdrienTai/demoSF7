<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\ContactRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class MailingSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
        $data = $event->data;
        $service = $data->getService();
        $email = (new TemplatedEmail())
            ->from($data->getEmail())
            ->to($service->getEmail() ?? 'generic@monsite.com')
            ->subject('Nouveau message ' .$service->getName() .' de la part de ' .$data->getName())
            ->text($data->getMessage())
            ->htmlTemplate('emails/contact.html.twig')
            ->locale('fr')
            ->context([
                'contact' => $data
        ]);

        $email->getHeaders()->addTextHeader('X-ip-address', $data->getIpAddress());
        $this->mailer->send($email);
    }

    public function onLogin(InteractiveLoginEvent $event) : void 
    {
        $user = $event->getAuthenticationToken()->getUser();

        if(!$user instanceof User) {
            return;
        }

        $email = (new Email())
            ->from('support@truc.fr')
            ->to($user->getEmail())
            ->subject('Nouvelle connexion oulalah sécurité blablabla')
            ->text('Olala vous vous êtes connecté, mais est-ce bien vous ? Un SMS vous a été envoyé et un agent de la poste cours en ce moment même vers votre domicile, pour savoir si vous êtes bien vous.');
        $this->mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onContactRequestEvent',
            InteractiveLoginEvent::class => 'onLogin'
        ];
    }
}
