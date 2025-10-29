<?php
// 代码生成时间: 2025-10-30 00:46:39
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class BulkFileRenamerCommand extends Command
{
    protected static $defaultName = 'app:rename-files';

    protected function configure()
    {
        $this
            ->setDescription('Batch file renamer tool.')
            ->addArgument('pattern', InputArgument::REQUIRED, 'Pattern to rename files with.')
            ->addArgument('directory', InputArgument::REQUIRED, 'Directory to scan for files.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $pattern = $input->getArgument('pattern');
        $directory = $input->getArgument('directory');

        $io->title('Bulk File Renamer');
        $io->text('Starting the renaming process...');

        // Load the rename rules from a YAML file.
        $renameRules = $this->loadRenameRules($pattern);
        if (empty($renameRules)) {
            $io->error('No rename rules found.');
            return Command::FAILURE;
        }

        // Find all files in the specified directory.
        $files = Finder::create()->files()->in($directory);

        // Rename each file according to the rules.
        foreach ($files as $file) {
            $filename = $file->getRelativePathname();
            $newName = $this->renameFile($filename, $renameRules);
            if ($newName !== $filename) {
                if (rename($file->getPathname(), $file->getPathname() . DIRECTORY_SEPARATOR . $newName)) {
                    $io->success(sprintf('Renamed %s to %s', $filename, $newName));
                } else {
                    $io->error(sprintf('Failed to rename %s', $filename));
                }
            }
        }

        $io->success('Renaming process completed.');

        return Command::SUCCESS;
    }

    private function loadRenameRules($pattern)
    {
        // Assuming the pattern is a filename with a .yml extension.
        $yamlFile = $pattern . '.yml';
        if (!file_exists($yamlFile)) {
            return [];
        }

        return Yaml::parseFile($yamlFile);
    }

    private function renameFile($filename, $renameRules)
    {
        foreach ($renameRules as $oldPattern => $newPattern) {
            if (preg_match($oldPattern, $filename)) {
                return preg_replace($oldPattern, $newPattern, $filename);
            }
        }

        return $filename;
    }
}
