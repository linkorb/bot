<?php

namespace Bot\Console\Command;

use RuntimeException;
use Bot\Model\Bot;
use Bot\Model\Intent;
use Bot\Model\Pattern;
use Bot\Model\Session;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChatCommand extends Command
{
    protected $bot;

    public function __construct(Bot $bot)
    {
        $this->bot = $bot;
        parent::__construct();
    }

    public function configure()
    {
        $this->setName('chat')
            ->setDescription('Chat with bot')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $running = true;


        // var_dump($this->bot->getIntents());

        while ($running) {
            $username = $this->bot->getVariable('username') ?? 'anonymous';
            $question = new Question('<info>' . $username . '</info>: ');

            $utterance = trim($helper->ask($input, $output, $question));
            // $output->writeLn('<comment>' . $utterance . '</comment>');
            switch ($utterance) {
                case 'exit':
                case 'e':
                case 'quit':
                case 'q':
                    $running = false;
                    break;
            }
            $response = $this->bot->handle($utterance);
            $text = $response->getText();
            if ($response->getStatus()!='OK') {
                $text = '<error>' . $text . '</error>';
            }
            $output->writeln('<comment>bot:</comment> ' . $text);
            $output->writeln('----------------------------------');
        }



    }
}
