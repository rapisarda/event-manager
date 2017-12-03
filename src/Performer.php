<?php

/*
 * (c) Nicolas Rapisarda <nicolas@rapisarda.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapisarda\EventManager;

/**
 *
 * @author nicolas
 */
class Performer {
    
    /**
     * @var array[Callable]
     */
    private $callables = [];
    
    /**
     * @var array[int]
     */
    private $priorities = [];
    
    /**
     * @var boolean
     */
    private $prioritised = false;
    
    /**
     * 
     * @param Callable $callable
     * @param int $priority
     */
    public function add(callable $callable, int $priority = 0){
        $this->callables[] = $callable;
        $this->priorities[] = $priority;
        if(0 !== $priority){
            $this->prioritised = true;
        }
    }
    
    public function remove(callable $callable){
        if(false !== $k = array_search($callable, $this->callables, true)){
            unset($this->callables[$k]);
            unset($this->priorities[$k]);
            return true;
        }
        return false;
    }
    
    public function perform(EventInterface $ev){
        if($this->prioritised){
            arsort($this->priorities);
        }
        foreach ($this->priorities as $k => $p){
            if($ev->isPropagationStopped()){
                return false;
            }
            $this->callables[$k]($ev);
        }
        return true;
    }

}
