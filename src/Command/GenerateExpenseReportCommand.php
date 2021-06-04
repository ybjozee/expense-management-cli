<?php

namespace App\Command;

use App\Exception\UnknownExpenseStatusException;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseReportGenerator;
use App\Service\SendgridMailer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateExpenseReportCommand extends Command {

    protected static $defaultName = 'generateExpenseReport';

    private ExpenseRepository $expenseRepository;
    private ExpenseReportGenerator $reportGenerator;
    private SendgridMailer $mailer;

    public function __construct(
        ExpenseRepository $expenseRepository,
        ExpenseReportGenerator $reportGenerator,
        SendgridMailer $mailer
    ) {

        parent::__construct();
        $this->expenseRepository = $expenseRepository;
        $this->reportGenerator = $reportGenerator;
        $this->mailer = $mailer;
    }

    protected function configure()
    : void {

        $this
            ->setDescription('Generates expense report')
            ->setHelp(
                'This command helps you generate an expense report based on provided arguments'
            )
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputOption(
                            'status',
                            's',
                            InputOption::VALUE_OPTIONAL,
                            'Only include expenses matching the specified status'
                        ),
                        new InputOption(
                            'mailTo',
                            'm',
                            InputOption::VALUE_OPTIONAL,
                            'Send the report as an attachment to the specified email address'
                        )
                    ]
                )
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    : int {

        $io = new SymfonyStyle($input, $output);

        $status = $input->getOption('status');
        $recipientEmailAddress = $input->getOption('mailTo');

        try {
            $expenses = $this->expenseRepository->findByStatus($status);
            $this->reportGenerator->generateExpenseReport($expenses);

            $filepath = $this->reportGenerator->getReportPath();
            $io->success("Expense report saved to $filepath");
            if (!is_null($recipientEmailAddress)) {
                $message =
                    <<<EOF
<html lang="en">
<body>
<p>Hi there,</p>
<p>Here's the latest expense report generated via the CLI</p>
</body>
</html>
EOF;
                $this->mailer->sendMail($recipientEmailAddress, 'Expense Report', $message, $filepath);
                $io->success("Expense report sent successfully to $recipientEmailAddress");
            }

            return Command::SUCCESS;
        }
        catch (UnknownExpenseStatusException $ex) {
            $io->error($ex->getMessage());

            return Command::INVALID;
        }

    }
}
