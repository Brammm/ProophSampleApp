<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use Swift_Mailer;
use Swift_Message;
use Todo\Domain\Todo\TodoRepository;

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
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(
        UserRepository $userRepository,
        TodoRepository $todoRepository,
        Swift_Mailer $mailer
    ) {
        $this->userRepository = $userRepository;
        $this->todoRepository = $todoRepository;
        $this->mailer = $mailer;
    }

    public function __invoke(NotifyUserOfAssignment $command): void
    {
        $user = $this->userRepository->findOneByUserId($command->userId());
        $todo = $this->todoRepository->findOneByTodoId($command->todoId());

        $mail = new Swift_Message();
        $mail->setFrom(['todo@example.org' => 'Todo']);
        $mail->setTo([(string) $user->email()]);
        $mail->setSubject('You\'ve been assigned to a Todo');
        $mail->setBody('Task is: ' . $todo->description());

        $this->mailer->send($mail);
    }
}
