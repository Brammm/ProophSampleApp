# Prooph Sample App

This is a small API built with [Prooph](http://getprooph.org/) as a test/learning experience. It mimicks the proophessor-do domain.

## Todo

- Queue commands on Rabbit
- Implement a ProcessManager to send emails (mailcatcher) when a todo is assigned
- Make commands more defensive by not using the payload trait, but rather give them a construct and making the payload ourself
- Add a generic CommandRequestHandler that uses attribute to determine the command to be handled
- Add request validation to the CommandRequestHandler
