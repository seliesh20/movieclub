<?php

namespace App\Command;

use App\Repository\MovieMeetingsRepository;
use App\Repository\MovieMeetingEmailRepository;
use App\Repository\MovieListRepository;
use App\Repository\UserRepository;
use App\Entity\MovieMeetings;
use App\Entity\MovieMeetingEmail;
use DateTime;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:meetings:email',
    description: 'Cron Job to create meeting Schedule Email',
)]
class MeetingEmailCommand extends Command
{
    private 
        $params,
        $movieMeetingRepository,
        $movieListRepository,
        $movieMeetingEmailRepository,
        $userRepository,
        $mailer;        

    public function __construct(string $name = null,    
        ParameterBagInterface $params,    
        MovieMeetingsRepository $movieMeetingRepository,
        MovieListRepository $movieListRepository,
        UserRepository $userRepository,
        MovieMeetingEmailRepository $movieMeetingEmailRepository,
        MailerInterface $mailer)
    {        
        $this->params = $params;
        $this->movieMeetingRepository = $movieMeetingRepository;
        $this->movieMeetingEmailRepository = $movieMeetingEmailRepository;
        $this->movieListRepository = $movieListRepository;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;        
        parent::__construct($name);        
    }
    protected function configure(
        
    ): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $daysofweek = $this->params->get('app.meeting_week_day');
        $time = $this->params->get('app.meeting_week_day_time');
        $todaysdayofweek = (new \DateTime())->format('w');
        $schedule_date = new \DateTime();           
        if($daysofweek > $todaysdayofweek){      
            $diff = ($daysofweek - $todaysdayofweek);
            $schedule_date->modify("+".$diff." day");
                  
            $meeting = $this->movieMeetingRepository->findOneBy([
                'meeting_date' => $schedule_date
            ]);

            $io->note(json_encode($meeting));
            if ($meeting instanceof MovieMeetings) {

                $users = $this->userRepository->findAll();
                foreach ($users as $user) {
                    $sendEmail = $this->movieMeetingEmailRepository->findBy([
                        'user' => $user,
                        'movie_meetings' => $meeting
                    ]);

                    if(is_array($sendEmail) && !count($sendEmail)){
                        //send Email
                        $email = (new TemplatedEmail())
                            ->from('selieshjks@gmail.com')
                            ->to('selieshjks@gmail.com')
                            ->subject($meeting->getMeetingTitle()) 
                            ->htmlTemplate('emails/send_invites.html.twig')                           
                            ->context([
                                'name'=>$user->getName(),
                                'hash'=> trim(base64_encode(base64_encode($user->getId()).':'.base64_encode($meeting->getId()))),
                                'meeting_title' => $meeting->getMeetingTitle(),
                                'base_url' => $this->params->get("app.host")                                
                            ]);
                        try{
                            $this->mailer->send($email);
                            //Add Record
                            $meetingEmail = new MovieMeetingEmail();
                            $meetingEmail->setMovieMeetings($meeting);
                            $meetingEmail->setUser($user);
                            $meetingEmail->setInvitationTime(new DateTime());
                            $meetingEmail->setStatus(1);
                            $this->movieMeetingEmailRepository->save($meetingEmail);
                        } catch(TransportExceptionInterface $e){

                        }                        
                    }
                    $io->note(json_encode($sendEmail));
                }
            }
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
