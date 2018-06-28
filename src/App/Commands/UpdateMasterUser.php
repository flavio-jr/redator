<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use App\Repositories\UserMasterRepository\Update\UserMasterUpdateInterface as UserMasterUpdate;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateMasterUser extends Command
{
    /**
     * The UserMaster update repository
     * @var UserMasterUpdate
     */
    private $userMasterUpdate;

    public function __construct(UserMasterUpdate $userMasterUpdate)
    {
        parent::__construct();
        $this->userMasterUpdate = $userMasterUpdate;
    }

    protected function configure()
    {
        $this->setName('user:update-master')
            ->setDescription('Updates the master user information');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('---------- MASTER USER UPDATE ----------');

        $helper = $this->getHelper('question');

        $nameQuestion = new Question('Enter the name: ', 'Master');
        $name = $helper->ask($input, $output, $nameQuestion);

        $usernameQuestion = new Question('Enter the username: ', 'master');
        $username = $helper->ask($input, $output, $usernameQuestion);

        $this->userMasterUpdate
            ->update([
                'name'     => $name,
                'username' => $username
            ]);

        $io = new SymfonyStyle($input, $output);
        $io->success('The user master was successfully updated');
    }
}