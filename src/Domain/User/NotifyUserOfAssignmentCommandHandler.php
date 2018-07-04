<?php

declare(strict_types=1);

namespace Todo\Domain\User;

use RuntimeException;
use Swift_Mailer;
use Swift_Message;
use Todo\Api\Projection\Todo\TodoFinder;
use Todo\Api\Projection\User\UserFinder;

final class NotifyUserOfAssignmentCommandHandler
{
    /**
     * @var UserFinder
     */
    private $userFinder;

    /**
     * @var TodoFinder
     */
    private $todoFinder;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    public function __construct(
        UserFinder $userFinder,
        TodoFinder $todoFinder,
        Swift_Mailer $mailer
    ) {
        $this->userFinder = $userFinder;
        $this->todoFinder = $todoFinder;
        $this->mailer = $mailer;
    }

    public function __invoke(NotifyUserOfAssignment $command): void
    {
        $user = $this->userFinder->findById((string) $command->userId());
        if ($user === null) {
            throw new RuntimeException('User not found');
        }
        $todo = $this->todoFinder->findById((string) $command->todoId());
        if ($todo === null) {
            throw new RuntimeException('Todo not found');
        }

        $mail = new Swift_Message();
        $mail->setFrom(['todo@example.org' => 'Todo']);
        $mail->setTo([$user->email]);
        $mail->setSubject('You\'ve been assigned to a Todo');
        $mail->setBody('Task is: ' . $todo->description);

        $this->mailer->send($mail);
    }
}
