<?php

/*
 * (c) Nicolas Rapisarda <nicolas@rapisarda.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapisarda\EventManager;

/**
 * Description of EventManager
 *
 * @author nicolas
 */
class EventManager implements EventManagerInterface{
    
    /**
     *
     * @var array [eventName => Performer]
     */
    private $listeners = [];
        
    /**
     * Attaches a listener to an event
     *
     * @param string $event the event to attach too
     * @param callable $callback a callable function
     * @param int $priority the priority at which the $callback executed
     * @return bool true on success false on failure
     */
    public function attach(string $event, callable $callback, int $priority = 0){
        if(!isset($this->listeners[$event])){
            $this->listeners[$event] = new Performer();
        }
        $this->listeners[$event]->add($callback, $priority);
    }


    /**
     * Detaches a listener from an event
     *
     * @param string $event the event to attach too
     * @param callable $callback a callable function
     * @return bool true on success false on failure
     */
    public function detach(string $event, $callback){
        if(isset($this->listeners[$event])){
            return $this->listeners[$event]->remove($callback);
        }
        return false;
    }

    /**
     * Clear all listeners for a given event
     *
     * @param  string $event
     * @return void
     */
    public function clearListeners($event){
        unset($this->listeners[$event]);
    }

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @param  array|object $argv
     */
    public function trigger($event, $target = null, $argv = array()){
        if(!($event instanceof EventInterface)){
            $event = new Event($event, $target, $argv);
        }
        if(isset($this->listeners[$event->getName()])){
            $this->listeners[$event->getName()]->perform($event);
        }
    }

}
