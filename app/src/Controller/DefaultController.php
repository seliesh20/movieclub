<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\MovieListRepository;
use App\Repository\MovieMeetingsRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Validator\Constraints\Date;

class DefaultController extends AbstractController
{
    #[Route('/{reactRouting}', name: 'home', defaults:["reactRouting"=>null])]
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    #[Route('/api/test', name: 'home_test')]
    public function test(
        MovieMeetingsRepository $movieMeetingRepository,
        MovieListRepository $movieListRepository): Response
    {
        $daysofweek = $this->getParameter('app.meeting_week_day');
        $time = $this->getParameter('app.meeting_week_day_time');
        $todaysdayofweek = (new \DateTime())->format('w');
        $schedule_date = new \DateTime();           
        if($daysofweek > $todaysdayofweek){            
            $diff = ($daysofweek - $todaysdayofweek);
            $schedule_date->modify("+".$diff." day");                   

            $movieMeetingCriteria = Criteria::create()
                ->where(Criteria::expr()->eq('meeting_date', new DateTime('2021-10-21')));
            $meetings = $movieMeetingRepository->matching($movieMeetingCriteria); 
            
            $movies = $movieListRepository->findRandomUnSeenMovie();

            /*if(!(is_array($meetings) && count($meetings))){ 
                $meeting = new MovieMeetings();
                $meeting->setMeetingTitle('MovieClub Meeting!!-'.$schedule_date->format('W, Y'));
                $meeting->setMeetingDate($schedule_date);
                $meeting->setMeetingTime($schedule_date);
                $meeting->setMovieList($movie); //random movie
            }*/           
            return new JsonResponse([
                'meetings'=>($movies),
                'movies'=>is_array($movies)?$movies[0]->getId():0                
            ]);
        } 
        return new JsonResponse([
            'daysofweek'=>$daysofweek,
            'todaysdayofweek' => $todaysdayofweek
        ]);
    }
}
