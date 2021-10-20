<?php

namespace App\Command;

use App\Entity\MovieMeetings;
use App\Entity\MovieList;
use App\Repository\MovieMeetingsRepository;
use App\Repository\MovieListRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\ContainerParametersHelper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:meetings',
    description: 'Cron Job to create meeting schedule',
)]
class MeetingsCommand extends Command
{
    private 
        $params,
        $movieMeetingRepository,
        $movieListRepository;

    public function __construct(string $name = null,    
        ParameterBagInterface $params,    
        MovieMeetingsRepository $movieMeetingRepository,
        MovieListRepository $movieListRepository)
    {        
        $this->params = $params;
        $this->movieMeetingRepository = $movieMeetingRepository;
        $this->movieListRepository = $movieListRepository;
        parent::__construct($name);        
    }
    
    protected function configure(): void
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
            //$io->note(sprintf('You passed an argument: %s', $arg1));
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
            $io->note($schedule_date->format("Y-m-dTH:i:s.uZ"));        

            $meetings = $this->movieMeetingRepository->findAll();

            $movie = $this->movieListRepository->findAll();

            /*if(!(is_array($meetings) && count($meetings))){ 
                $meeting = new MovieMeetings();
                $meeting->setMeetingTitle('MovieClub Meeting!!-'.$schedule_date->format('W, Y'));
                $meeting->setMeetingDate($schedule_date);
                $meeting->setMeetingTime($schedule_date);
                $meeting->setMovieList($movie); //random movie
            }*/
            $io->note(json_encode($meetings));
            $io->note(json_encode($movie));            
        } 
        
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
