<?php


namespace App\Service;


use App\Entity\Loan;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * MailService constructor.
     * @param MailerInterface $mailer
     * @param UrlGeneratorInterface $router
     */
    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function notifyLoanCreation(Loan $loan): void
    {
        $email = (new Email())
            ->from('loans@prestamos.com')
            ->to($loan->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Una nueva solicitud ha sido creada!')
            ->text('Una nueva solicitud ha sido creada!')
            ->html(sprintf('<p>A new loan from %s %s had been created!</p>', $loan->getFirstName(), $loan->getLastName()));

        $this->mailer->send($email);
    }

    public function notifyUserCreation(User $user): void
    {
        $url = $this->router->generate('check_token', [
            'token' => $user->getValidationToken()
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        $email = (new Email())
            ->from('accounts@prestamos.com')
            ->to($user->getEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Su nueva cuenta ha sido creada!')
            ->text('Su nueva cuenta ha sido creada!')
            ->html(sprintf('<p>Hola %s %s tu cuenta ha sido creada! Click <a href="%s">aquí</a> para confirmar</p>', $user->getFirstName(), $user->getLastName(), $url));

        $this->mailer->send($email);
    }

}