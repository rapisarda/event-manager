<?php

/*
 * (c) Nicolas Rapisarda <nicolas@rapisarda.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rapisarda\EventManager\Test;

use Rapisarda\EventManager\EventManager;
use Rapisarda\EventManager\Event;


/**
 * Description of EventManagerTest
 *
 * @author nicolas
 */
class EventManagerTest extends \PHPUnit\Framework\TestCase {
    
    /**
     *
     * @var EventManager
     */
    private $eventManager;
    
    public function setUp() {
        $this->eventManager = new EventManager;
        parent::setUp();
    }
    
    public function testTrigger(){
        $eventA = new Event('a');
        $eventB = new Event('b');
        $this->eventManager->attach($eventA->getName(), function() { echo 'a';});
        $this->eventManager->attach($eventB->getName(), function() { echo 'b';});
        $this->eventManager->trigger($eventA->getName());
        $this->eventManager->trigger($eventB->getName());
        $this->expectOutputString('ab');
    }
    
    public function testPriority(){
        $event = new Event('event');
        $this->eventManager->attach($event->getName(), function() { echo '4'; }, -100);
        $this->eventManager->attach($event->getName(), function() { echo '0'; }, 1000);
        $this->eventManager->attach($event->getName(), function() { echo '2'; });
        $this->eventManager->trigger($event->getName());
        $this->eventManager->attach($event->getName(), function() { echo '1'; } , 999);
        $this->eventManager->attach($event->getName(), function() { echo '3'; } , -99);
        $this->eventManager->trigger($event->getName());
        $this->expectOutputString('02401234');
    }
    
    public function testDetach(){
        $eventA = new Event('a');
        $this->eventManager->attach($eventA->getName(), function() { echo 'a';});
        $this->eventManager->attach($eventA->getName(), $detached = function() { echo 'b';});
        $this->eventManager->detach($eventA->getName(), $detached);
        $this->eventManager->trigger($eventA->getName());
        $this->expectOutputString('a');
    }
    
    public function testClearListeners(){
        $eventA = new Event('a');
        $eventB = new Event('b');
        $this->eventManager->attach($eventA->getName(), function() { echo 'a';});
        $this->eventManager->attach($eventB->getName(), function() { echo 'b';});
        $this->eventManager->clearListeners($eventA->getName());
        $this->eventManager->trigger($eventA->getName());
        $this->eventManager->trigger($eventB->getName());
        $this->expectOutputString('b');
    }
    
    public function testStopPropagation(){
        $event = new Event('event');
        $this->eventManager->attach($event->getName(), function() { echo '3';}, -100);
        $this->eventManager->attach($event->getName(), function() { echo '1';}, 1000);
        $this->eventManager->attach($event->getName(), function(Event $e) { 
            echo '2'; 
            $e->stopPropagation(true);
        });
        $this->eventManager->trigger($event->getName());
        $this->expectOutputString('12');
    }

}
