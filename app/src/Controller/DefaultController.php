<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

use App\Repository\MovieListRepository;
use App\Repository\MovieMeetingsRepository;
use App\Repository\UserRepository;
use App\Repository\MovieMeetingEmailRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

class DefaultController extends AbstractController
{
    #[Route('/{reactRouting}', name: 'home', defaults:["reactRouting"=>null])]
    public function index(): Response
    {
        return $this->render('default/index.html.twig');
    }

    #[Route('/accept-invite/{reactRouting}', name: 'accept_invite')]
    public function acceptInvite(
        Request $request,
        MovieMeetingEmailRepository $movieMeetingEmailRepository,
        UserRepository $userRepository,
        MovieMeetingsRepository $movieMeetingsRepository,
        MailerInterface $mailer):Response
    {
        $explodes = explode(':', base64_decode(explode('/', $request->getRequestUri())[2]));
        $user_id = base64_decode($explodes[0]);
        $meeting_id = base64_decode($explodes[1]);

        $user = $userRepository->find((int)$user_id);
        $meeting = $movieMeetingsRepository->find($meeting_id);

        //update MovieMeetingEmail
       $meetingEmail = $movieMeetingEmailRepository->findOneBy([
            'movie_meetings' => $meeting,
            'user' => $user
        ]);
        //Generate ICS file
        if(is_object($meetingEmail)){
            $fs = new Filesystem();

            //temporary folder, it has to be writable
            $tmpFolder = '/var/www/tmp/';            

            //the name of your file to attach
            $fileName = 'meeting.ics';
            $meetingDate = $meetingEmail->getMovieMeetings()
                ->getMeetingDate();
            $time = explode(':', $meetingEmail->getMovieMeetings()
            ->getMeetingTime()->format("H:i:s"));
            $meetingDate->setTime($time[0], $time[1], $time[2]);
            $endtime = clone($meetingDate);
            $endtime->modify("+2 hours");

            $icsContent = "
                BEGIN:VCALENDAR
                VERSION:2.0
                CALSCALE:GREGORIAN
                METHOD:REQUEST
                BEGIN:VEVENT
                DTSTART:".$meetingDate->format('Ymd\THis')."
                DTEND:".$endtime->format('Ymd\THis')."
                DTSTAMP:".$meetingDate->format('Ymd\THis')."
                ORGANIZER;CN=MovieClub:mailto:do-not-reply@example.com
                UID:".rand(5, 1500)."
                ATTENDEE;PARTSTAT=NEEDS-ACTION;RSVP= TRUE;CN=Sample:".$user->getEmail()."
                DESCRIPTION:".$user->getName()." requested Phone/Video Meeting Request
                LOCATION: Phone/Video
                SEQUENCE:0
                STATUS:CONFIRMED
                SUMMARY:Meeting has been scheduled by ".$user->getName()."
                TRANSP:OPAQUE
                END:VEVENT
                END:VCALENDAR"
            ;

            //creation of the file on the server
            $icfFile = $fs->dumpFile($tmpFolder.$fileName, $icsContent);
            // Send Email
            $email = (new TemplatedEmail())
                ->from('selieshjks@gmail.com')
                ->to('selieshjks@gmail.com')
                ->subject($meeting->getMeetingTitle())
                ->attachFromPath($tmpFolder.'/'.$fileName, "meeting.ics", "text/calendar")
                ->context([
                    'name' => $user->getName(),
                    'hash' => trim(base64_encode(base64_encode($user->getId()) . ':' . base64_encode($meeting->getId()))),
                    'meeting_title' => $meeting->getMeetingTitle(),
                    'base_url' => $this->getParameter("app.host")
                ]);
                try{
                    $mailer->send($email);
                    // update Record
                    $meetingEmail->setStatus(1);
                    $movieMeetingEmailRepository->save($meetingEmail);
                } catch(TransportExceptionInterface $e){
                    return new JsonResponse([            
                        'status'=> 'failure',
                        'message' => "No Meeting for schedule"
                    ]);
                }     

            $fs->remove(array('file', $tmpFolder, $fileName));
            //Send Email
            return new JsonResponse([            
                'status'=> 'success',
                'message'=>'meeting scheduled'
            ]);            
        }
        //Send Email
        return new JsonResponse([            
            'status'=> 'failure',
            'message' => "No Meeting for schedule"
        ]);
    }

    #[Route('/reject-invite/{reactRouting}', name: 'reject_invite')]
    public function rejectInvite(
        Request $request,
        MovieMeetingsRepository $movieMeetingsRepository,
        MovieMeetingEmailRepository $movieMeetingEmailRepository,
        UserRepository $userRepository):Response
    {
        $explodes = explode(':', base64_decode(explode('/', $request->getRequestUri())[2]));
        $user_id = base64_decode($explodes[0]);
        $meeting_id = base64_decode($explodes[1]);

        $user = $userRepository->find((int)$user_id);
        $meeting = $movieMeetingsRepository->find($meeting_id);

        //update MovieMeetingEmail
        $meetingEmail = $movieMeetingEmailRepository->findOneBy([
            'movie_meetings' => $meeting,
            'user' => $user
        ]);

        //update MovieMeetingEmail
        $meetingEmail->setStatus(2);
        $movieMeetingEmailRepository->save($meetingEmail);

        return new JsonResponse([            
            'user_id'=> $user_id,
            'meeting_id'=> $meeting_id
        ]);
    }    
}
