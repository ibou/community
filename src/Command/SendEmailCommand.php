<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\Mailer\Sender\SenderInterface;

class SendEmailCommand extends Command
{
    protected static $defaultName = 'send:email';

    /**
     * @var SenderInterface
     */
    private $sender;
    public function __construct(SenderInterface $sender)
    {
        $this->sender = $sender;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $io->note(sprintf('You passed an argument:'));
        $this->sendEmail();
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }

    public function sendEmail(): void
    {
        $reference = "un deux 355555";
        $this->sender->send('noreply@example.com', ['jeffrey.verreckt@gmail.com'], 'email/references.html.twig', [
            'reference' => $reference
        ]);
    }
}
