<?php

namespace MauticPlugin\HubsFacebookAdsBundle\Command;

use Mautic\CoreBundle\Command\ModeratedCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCustomAudienceLeadsCommand extends ModeratedCommand
{
    /**
     * Configure the command.
     */
    public function configure()
    {
        $this->setName('Hubs:update:customaudiance')
                ->setDescription('Update custom audiance')
                ->setHelp(
                        <<<'EOT'
                help text goes here
EOT
                )
                ->addOption(
                        '--batch-limit', null, InputOption::VALUE_REQUIRED, 'The maximum number of iterations the cron runs per cycle. This value gets distributed by the number of records to be updated'
                )
                ->addOption('--customaudience-id', '-i', InputOption::VALUE_OPTIONAL, 'Specific ID to rebuild. Defaults to all.', false);
        parent::configure();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $translator = $this->getContainer()->get('translator');
        /** @var \MauticPlugin\HubsFacebookAdsBundle\Model\CustomAudienceModel $model */
        $model = $this->getContainer()->get('hubs.fbads.model.customaudiance');

        // set the repository
        $this->customaudianceRepo = $model->getRepository();

        $translator->setLocale($this->getContainer()->getParameter('mautic.locale'));

        // get the mid from the cli
        $batchLimit = $input->getOption('batch-limit');
        $id         = $input->getOption('customaudience-id');

        if ($id) {
            $customAudiance = $model->getEntity($id);
            if ($customAudiance !== null) {
                $output->writeln('<info>'.$translator->trans('hubs.customaudiance.list.rebuild.rebuilding', ['%id%' => $id]).'</info>');
                $processed = $model->rebuildCustomAudianceListLeads($customAudiance, $batchLimit, $output);
                $output->writeln(
                        '<comment>'.$translator->trans('hubs.customaudiance.list.rebuild.leads_affected', ['%leads%' => $processed]).'</comment>'
                );
            } else {
                $output->writeln('<error>'.$translator->trans('hubs.customaudiance.list.rebuild.not_found', ['%id%' => $id]).'</error>');
            }
        } else {
            $lists = $model->getEntities(
                    [
                        'iterator_mode' => true,
                    ]
            );

            while (($l = $lists->next()) !== false) {
                // Get first item; using reset as the key will be the ID and not 0
                $l = reset($l);

                $output->writeln('<info>'.$translator->trans('hubs.customaudiance.list.rebuild.rebuilding', ['%id%' => $l->getId()]).'</info>');

                $processed = $model->rebuildCustomAudianceListLeads($l, $batchLimit, $output);
                $output->writeln(
                        '<comment>'.$translator->trans('hubs.customaudiance.list.rebuild.leads_affected', ['%leads%' => $processed]).'</comment>'."\n"
                );

                unset($l);
            }

            unset($lists);
        }

        return 0;
    }
}
