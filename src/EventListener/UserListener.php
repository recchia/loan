<?php


namespace App\EventListener;


use App\Entity\User;
use App\Service\MailService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * UserListener constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TokenStorageInterface $tokenStorage
     * @param MailService $mailService
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenStorageInterface $tokenStorage, MailService $mailService)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenStorage = $tokenStorage;
        $this->mailService = $mailService;
    }

    /**
     * @ORM\PrePersist()
     *
     * @param User $user
     * @param LifecycleEventArgs $eventArgs
     * @throws Exception
     */
    public function prePersist(User $user, LifecycleEventArgs $eventArgs): void
    {
        /** @var User $actualUser */
        $actualUser = $this->tokenStorage->getToken()->getUser();

        if (in_array('ROLE_SUPERVISOR', $actualUser->getRoles(), true)) {
            $user->setAffiliate($actualUser->getAffiliate());
            $user->setCommission($actualUser->getAffiliate()->getCommission());
            $user->setRoles(['ROLE_USER']);
            $user->setUpdatedAt(new \DateTime());
        }

        if ($user->getIsActive() !== true) {
            $random = sha1(random_bytes(12));
            $user->setValidationToken($random);
        }

        $this->encodePassword($user);
    }

    /**
     * @ORM\PostPersist()
     *
     * @param User $user
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(User $user, LifecycleEventArgs $eventArgs): void
    {
        $this->mailService->notifyUserCreation($user);
    }

    /**
     * @ORM\PreUpdate()
     *
     * @param User $user
     * @param PreUpdateEventArgs $eventArgs
     * @throws Exception
     */
    public function preUpdate(User $user, PreUpdateEventArgs $eventArgs): void
    {
        if (!empty($user->getPlainPassword())) {
            $user->setUpdatedAt(new \DateTime());
            $this->encodePassword($user);
            $em = $eventArgs->getEntityManager();
            $meta = $em->getClassMetadata(User::class);
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $user);
        }
    }

    protected function encodePassword(User $user): void
    {
        $encoded = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($encoded);
    }

}