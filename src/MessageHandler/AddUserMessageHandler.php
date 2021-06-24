<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\AddUserMessage;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AddUserMessageHandler implements MessageHandlerInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(AddUserMessage $message): User
    {
        $user = $this->userRepository->find($message->getId());

        if (! $user instanceof User) {
            $user = new User();
            $user->setId($message->getId());

            $this->userRepository->save($user);
        }

        $user->setUsername($message->getUsername());
        $user->setFirstName($message->getFirstName());
        $user->setLastName($message->getLastName());

        return $user;
    }
}
