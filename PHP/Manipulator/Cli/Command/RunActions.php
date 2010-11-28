<?php

namespace PHP\Manipulator\Cli\Command;

use PHP\Manipulator\Config;
use PHP\Manipulator;
use PHP\Manipulator\Token;
use PHP\Manipulator\FileContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpFoundation\UniversalClassLoader;

/**
 * @package PHP\Manipulator
 * @license http://opensource.org/licenses/bsd-license.php The BSD License
 * @link    http://github.com/robo47/php-manipulator
 * @version @pear_package_version@ (@pear_package_git_hash@)
 * @uses    \Symfony\Components\Console\Input\InputInterface
 * @uses    \Symfony\Components\Console\Output\OutputInterface
 * @uses    \Symfony\Components\Console\Command\Command
 * @uses    \Symfony\Components\Console\Input\InputOption
 * @uses    \Symfony\Foundation\UniversalClassLoader
 * @uses    \PHP\Manipulator\FileContainer
 */
class RunActions extends Command
{
    protected function configure()
    {
        $this->setName('runActions');
        $this->setDescription('Runs actions from config an runs them');
        $def = array(
            new InputOption('--config', null, InputOption::PARAMETER_OPTIONAL, 'the config used', 'phpmanipulator.xml')
        );
        $this->setDefinition($def);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getOption('config');

        $config = $this->_getConfig($configFile);

        $this->_setupLoader($config);

        $files = $config->getFiles();
        $actions = $config->getActions();

        $filesCount = count($files);
        $actionsCount = count($actions);

        if ($filesCount === 0) {
            $output->write('No files found' . PHP_EOL);
            return;
        }
        if ($actionsCount === 0) {
            $output->write('No actions found' . PHP_EOL);
            return;
        }

        $filesDone = 1;
        $output->write('Processing ' . $filesCount . ' files and ' . $actionsCount . ' actions' . PHP_EOL);
        foreach ($files as $file) {
            $output->write('File: ' . $file . ' (' . $filesDone . '/' . $filesCount . ')' . PHP_EOL);
            $container = new FileContainer($file);
            foreach ($actions as $action) {
                /* @var $action \PHP\Manipulator\Action */
                $action->run($container);
                $output->write('    Action: ' . get_class($action) . PHP_EOL);
            }
            $container->save();
            $filesDone++;
        }

        $output->write(PHP_EOL . 'Applied all actions ' . PHP_EOL);
    }

    /**
     * @param string $configFile
     * @return \PHP\Manipulator\Config\Xml
     */
    protected function _getConfig($configFile)
    {
        try {
            return Config::factory('xml', $configFile, true);
        } catch (\Exception $e) {
            throw new \Exception('Unable to load config: ' . $configFile . PHP_EOL . 'error: ' . $e->getMessage());
        }
    }

    /**
     * @param Config $config
     */
    protected function _setupLoader(Config $config)
    {
        $loaders = spl_autoload_functions();
        $universalLoader = null;
        foreach($loaders as $loader) {
            if ($loader instanceof UniversalClassLoader) {
                $universalLoader = $loader;
                break;
            }
        }
        if (null === $universalLoader) {
            $loader = new UniversalClassLoader();
        }

        $loader->registerNamespaces($config->getClassLoaders());
    }
}