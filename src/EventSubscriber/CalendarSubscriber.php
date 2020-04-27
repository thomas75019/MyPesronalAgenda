<?php

namespace App\EventSubscriber;


use App\Repository\InterviewRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar, InterviewRepository $repository)
    {

        $interviews = $repository->findAll();

        foreach ($interviews as $interview)
        {
            $calendar->addEvent(new Event(
                $interview->getType() . ' for ' . $interview->getApplication()->getCompanyName(),
                $interview->getDate()
            ));
        }

    }

}