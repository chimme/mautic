<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class RoboFile extends \Robo\Tasks
{

    /**
     * Bootstrap installation
     *
     */
    public function bootstrap()
    {

        $this->stopOnFail(true);

        $this->say("Bootstrapping...");
        $this->_configComposer();
        $this->taskComposerInstall()->run();
        $this->configure();
    }

    /**
     * Configure Mautic installation
     *
     */
    public function configure()
    {
        $this->taskFileSystemStack()
            ->copy('local.template.php', './app/config/local.php', true)
            ->run();

        $values = array();

        $values['site_url'] = $this->askDefault('Please enter the URL for this Mautic installation', 'http://192.168.33.10/');
        $values['db_name'] = $this->askDefault('Database name', 'homestead');
        $values['db_user'] = $this->askDefault('Database user', 'homestead');
        $values['db_password'] = $this->askDefault('Database password', 'secret');

        $shared_path = $this->askDefault('Please enter the path where shared data should be stored (cache, logs, spool)', '/vagrant/app');
        $values['cache_path'] = $shared_path."/cache";
        $values['log_path'] = $shared_path."/logs";
        $values['mailer_spool_path'] = $shared_path."/spool";

        $values['upload_dir'] = $this->askDefault('Please enter the path where uploads should be stored', '/vagrant/media/files');

        $values['secret_key'] = $this->_randomString();

        $fields = array();
        foreach ($values as $key => $value) {
            $fields[] = '##'.$key.'##';
        }

        $this->taskReplaceInFile('./app/config/local.php')
            ->from($fields)
            ->to($values)
            ->run();
    }

    /**
     * Initialize database with fixtures
     *
     */
    public function init()
    {

        # create the database
        $this->_exec('php app/console doctrine:database:drop --if-exists --force');

        # create the database
        $this->_exec('php app/console doctrine:database:create');

        # create the schema
        $this->_exec('php app/console doctrine:schema:create');

        # creates the admin account and role
        $this->_exec('php app/console doctrine:fixtures:load --fixtures=app/bundles/InstallBundle/InstallFixtures/ --fixtures=plugins/HubsCoreBundle/InstallFixtures/ --no-interaction');

    }

    /**
     * Deploy themes into the Mautic theme folder
     *
     */
    public function deployThemes()
    {
        $repo_path = $this->askDefault('Please enter the path to the themes repository', '/55hubs-themes');

        $fs = new Filesystem();
        if (!$fs->exists($repo_path)) {
            return Robo\Result::error($this, "Path to themes repository not found!");
        }

        $theme_pattern = $this->ask('Please enter pattern of themes you want to deploy (example: 55weeks*)');

        // Use the Finder to get all folder from the top-level hierarchy
        $finder = new Finder();
        $finder->directories()->depth('== 0');

        // if a pattern was specified, Finder will use it to exlucd all other folder
        if ($theme_pattern) {
            $finder->name($theme_pattern);
        }

        $finder->in($repo_path);

        $theme_path = __DIR__."/themes";

        foreach ($finder as $theme) {
            $to = $theme_path."/".$theme->getBasename();
            $this->say("Symlink from ".$theme." to ".$to);
            if (!$fs->exists($to)) {
                $this->taskFileSystemStack()
                    ->symlink($theme, $to)
                    ->run();
            }
            else {
                $this->say("Symlink ".$to." already exists, skipping..");
            }
        }

    }

    /**
     * Update the system after you pulled code from Github
     *
     */
    public function update()
    {
        # Clean cache
        $this->_cleanDir($this->_getCachePath());

        # Install composer dependencies
        $this->taskComposerInstall()->run();

        # Run Doctrine migrations
        $this->_exec('php app/console doctrine:migrations:migrate -n --allow-no-migration');
    }

    /**
     * Read path to cache directory from local config
     *
     */
    private function _getCachePath() {
        include __DIR__.'/app/config/local.php';
        return $parameters['cache_path'];
    }

    /**
     * Configure composer with Github token
     *
     */
    private function _configComposer()
    {

            $this->stopOnFail(false);

            $result = $this->_exec("composer config -g github-oauth.github.com");
            if ($result->wasSuccessful() == false) {
                $token = $this->ask("Enter your Github token");
                $this->taskExecStack()
                    ->stopOnFail()
                    ->exec("composer config -g github-oauth.github.com $token")
                    ->run();
            }

            $this->stopOnFail(true);

    }

    /**
     * Generate a random string
     *
     * @param int $length
     * @return string
     */
    private function _randomString($length = 32) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

}
