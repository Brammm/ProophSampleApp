# Prooph Sample App

This is a small API built with [Prooph](http://getprooph.org/) as a test/learning experience. It mimicks the proophessor-do domain.

## Todo

- Queue commands on Rabbit
- Implement a ProcessManager to send emails (mailcatcher) when a todo is assigned
- Add a generic CommandRequestHandler that uses attribute to determine the command to be handled
- Add request validation to the CommandRequestHandler
