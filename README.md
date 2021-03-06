# tactician-prioritycommand-plugin
Tactician plugin that allows adding a priority to a command which influences when and in what order commands will be executed

[![Build Status](https://travis-ci.org/ConnectHolland/tactician-prioritycommand-plugin.svg?branch=master)](https://travis-ci.org/ConnectHolland/tactician-prioritycommand-plugin)
[![Coverage Status](https://coveralls.io/repos/RonRademaker/tactician-prioritycommand-plugin/badge.svg?branch=master&service=github)](https://coveralls.io/github/RonRademaker/tactician-prioritycommand-plugin?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3e8f0f6d-43d3-4761-ae75-14461264b8df/mini.png)](https://insight.sensiolabs.com/projects/3e8f0f6d-43d3-4761-ae75-14461264b8df)
[![Latest Stable Version](https://poser.pugx.org/connectholland/tactician-prioritycommand-plugin/v/stable)](https://packagist.org/packages/connectholland/tactician-prioritycommand-plugin)
[![Total Downloads](https://poser.pugx.org/connectholland/tactician-prioritycommand-plugin/downloads)](https://packagist.org/packages/connectholland/tactician-prioritycommand-plugin)
[![Latest Unstable Version](https://poser.pugx.org/connectholland/tactician-prioritycommand-plugin/v/unstable)](https://packagist.org/packages/connectholland/tactician-prioritycommand-plugin)
[![License](https://poser.pugx.org/connectholland/tactician-prioritycommand-plugin/license)](https://packagist.org/packages/connectholland/tactician-prioritycommand-plugin)

# Concept
The plugin adds Middleware that allows you to prioritize your commands. To give priority to a command let it extend from one of the command implementations in this library:

Default supported command types:
- AbstractRequestCommand (bus, you may take a detour, but you may not start a new round)
- AbstractSequenceCommand (bus, you may take a detour, but nobody gets off before I do)
- AbstractUrgentCommand (take me to my destination asap)

You can create an interface to your event dispatcher (one for symfony ships with this library). You should attach the PriorityMiddleware::REQUEST queue to some event you always dispatch, preferably after output is sent to your users.

# Suggested priorities
Obviously you're free to give your commands any priority you like, but these guidelines may help:

- Urgent: anything that affects the output you send to your user OR anything that affects the behavior of incoming request
- Request: anything that affects any subsequent requests from your user
- Sequence: anything that affects the behavior of following commands (i.e. because it sets an id in your database something else depends on)

# Issues
Commands are not executed immediately which makes it impossible to return values. If you rely on the return value of a command, you can't make that command a priority command without breaking your application. The fix for this is to add an event listener before putting the command on the bus and dispatching an event from the handler which passes the return value.
Example, code with return value

```
$result = $commandbus->handle($command);
echo "The result is.... {$result}";
```

Without the return value:

```
$eventdispatcher->addListener('when_my_commandhandler_is_done', function($event) {
    echo "The result is.... {$event->getReturnValue()}";
});
$result = $commandbus->handle($priorityCommand);
```
