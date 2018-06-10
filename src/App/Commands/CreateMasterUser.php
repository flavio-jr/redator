<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repositories\UserMasterRepository\Store\UserMasterStoreInterface as UserMasterStore;
use Symfony\Component\Console\Style\SymfonyStyle;

final class CreateMasterUser extends Command
{
    /**
     * The user store repository
     * @var UserMasterStore
     */
    private $userMasterStore;

    public function __construct(UserMasterStore $userMasterStore)
    {
        parent::__construct();
        $this->userMasterStore = $userMasterStore;
    }

    protected function configure()
    {
        $this->setName('user:create-master')
            ->setDescription('Creates the master user if it doesn\'t exist');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $userMaster = $this->userMasterStore
            ->store();

        $io->success('The user master was successfully created');
    }
}