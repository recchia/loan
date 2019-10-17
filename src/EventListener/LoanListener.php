<?php


namespace App\EventListener;


use App\Entity\Loan;
use App\Entity\Status;
use App\Entity\User;
use App\Service\LoanService;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoanListener
{
    /**
     * @var LoanService
     */
    private $service;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * LoanListener constructor.
     * @param LoanService $service
     * @param MailService $mailService
     * @param TokenStorageInterface $tokenStorage
     * @param SessionInterface $session
     */
    public function __construct(
        LoanService $service,
        MailService $mailService,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    )
    {
        $this->service = $service;
        $this->mailService = $mailService;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    /**
     * @ORM\PrePersist()
     *
     * @param Loan $loan
     * @param LifecycleEventArgs $eventArgs
     * @throws Exception
     */
    public function prePersistHandler(Loan $loan, LifecycleEventArgs $eventArgs): void
    {
        $percentage = 0;
        $amount = 0;

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();
        $status = $this->getStatus($user, $eventArgs->getEntityManager());

        if ($user->getCommission() !== null) {
            $percentage = $user->getCommission()->getPercentage() ?? 0;
            $amount = $loan->getAmountRequest() * ($percentage / 100);
        }

        $loan->setUser($user);
        $loan->setCommissionPercentage($percentage);
        $loan->setCommissionAmount($amount);
        $loan->setStatus($status);
        $loan->setCreatedAt(new \DateTime());
        $this->makeRequest($loan);
    }

    /**
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return Status
     */
    protected function getStatus(User $user, EntityManagerInterface $entityManager): Status
    {
        $status = Status::CHECK_PENDING;

        if (in_array('ROLE_REFERENCER', $user->getRoles(), true)) {
            $status = Status::PROSPECT;
        }

        if (in_array('ROLE_HUMAN_RESOURCES', $user->getRoles(), true)) {
            $status = Status::SIGN_PENDING;
        }

        return $entityManager->getRepository(Status::class)->find($status);
    }

    /**
     * @ORM\PostPersist()
     *
     * @param Loan $loan
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersistHandler(Loan $loan, LifecycleEventArgs $eventArgs): void
    {
        $this->mailService->notifyLoanCreation($loan);
    }

    protected function makeRequest(Loan $loan): void
    {
        $response = $this->service->makeLegacyRequest($loan);

        if ($response->getStatusCode() === Response::HTTP_CREATED) {
            $data = json_decode($response->getBody(), true);
            $loan->setLegacyClientId($data['client_id']);
            $loan->setLegacyLoanId($data['loan_id']);
            $loan->setSent(true);

            $this->session->getFlashBag()->add(
                'success',
                sprintf('La solicitud de  %s %s ha sido enviada!', $loan->getFirstName(), $loan->getLastName())
            );
        }

        $data = json_decode($response->getBody(), true);

        $meesage = $data['message'] ?? '';

        $this->session->getFlashBag()->add('danger', sprintf('Prestamos 911: %s', $meesage));
    }

}