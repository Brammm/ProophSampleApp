<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Todo\Domain\Todo\TodoRepository;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

final class NotifyUserOfAssignmentCommandHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TodoRepository
     */
    private $todoRepository;

    /**
     * @var TransportInterface
     */
    private $transport;

    public function __construct(
        UserRepository $userRepository,
        TodoRepository $todoRepository,
        TransportInterface $transport
    ) {
        $this->userRepository = $userRepository;
        $this->todoRepository = $todoRepository;
        $this->transport = $transport;
    }

    public function __invoke(NotifyUserOfAssignment $command): void
    {
        $user = $this->userRepository->findOneByUserId($command->userId());
        $todo = $this->todoRepository->findOneByTodoId($command->todoId());

        $mail = new Message();
        $mail->setFrom('todo@example.org', 'Todo');
        $mail->addTo((string) $user->email());
        $mail->setSubject('You\'ve been assigned to a Todo');
        $mail->setBody('Task is: ' . $todo->description());

        $this->transport->send($mail);
    }
}
