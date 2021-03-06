<?php

namespace ConnectHolland\Tactician\PriorityPlugin\Command;

use ConnectHolland\Tactician\PriorityPlugin\Queue\Manager;

/**
 * Command that may be executed at any time, but no other command may be executed before this.
 *
 * Useful for things that don't effect output, but where it's result is important for later commands
 * Example: storing something in a database
 *
 * @author Ron Rademaker
 */
abstract class AbstractSequenceCommand implements PriorityCommandInterface
{
    /**
     * getQueue.
     *
     * Gets the queue to put the command in
     *
     * @since 1.0
     *
     * @return string
     * */
    public function getQueue()
    {
        return Manager::SEQUENCE;
    }
}
