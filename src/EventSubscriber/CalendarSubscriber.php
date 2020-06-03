<?php

namespace App\EventSubscriber;


use App\Repository\InterviewRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $repository;

    public function __construct(InterviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();

        $interviews = $this->repository
            ->createQueryBuilder('interview')
            ->where('interview.date BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();

        foreach ($interviews as $interview)
        {
            $bookingEvent = new Event(
                $interview->getType(),
                $interview->getDate()
            );

            $calendar->addEvent($bookingEvent);
        }
    }
}