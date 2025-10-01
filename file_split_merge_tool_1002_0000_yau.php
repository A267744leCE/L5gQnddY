<?php
// 代码生成时间: 2025-10-02 00:00:36
require_once 'vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
# NOTE: 重要实现细节
use Symfony\Component\Console\Style\SymfonyStyle;
# 改进用户体验

class FileSplitMergeTool extends Command
{
    protected static $defaultName = 'app:file-split-merge';
# FIXME: 处理边界情况

    /**
     * Configures the command and its arguments.
     */
    protected function configure()
    {
        $this
            ->setDescription('File Split Merge Tool')
            ->setHelp('This command allows you to split and merge files...')
            ->addArgument('operation', InputArgument::REQUIRED, 'Split or Merge operation')
            ->addArgument('file_path', InputArgument::REQUIRED, 'File path for operation')
            ->addArgument('chunk_size', InputArgument::OPTIONAL, 'Size of each chunk in bytes (for split operation)', 1048576);
    }

    /**
     * Executes the command.
     *
# 优化算法效率
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
# NOTE: 重要实现细节
    protected function execute(InputInterface $input, OutputInterface $output)
    {
# TODO: 优化性能
        $io = new SymfonyStyle($input, $output);
        $operation = $input->getArgument('operation');
        $filePath = $input->getArgument('file_path');
        $chunkSize = $input->getArgument('chunk_size');

        if (!file_exists($filePath)) {
            $io->error('File not found');
            return Command::FAILURE;
        }

        switch ($operation) {
            case 'split':
                return $this->splitFile($filePath, $chunkSize, $io);
            case 'merge':
                return $this->mergeFiles($filePath, $io);
            default:
                $io->error('Invalid operation');
                return Command::FAILURE;
        }
    }

    /**
     * Splits the file into chunks.
# 扩展功能模块
     *
# TODO: 优化性能
     * @param string $filePath
     * @param int $chunkSize
# NOTE: 重要实现细节
     * @param SymfonyStyle $io
# 改进用户体验
     * @return int
     */
    private function splitFile(string $filePath, int $chunkSize, SymfonyStyle $io): int
    {
        $fileSize = filesize($filePath);
        $fileHandle = fopen($filePath, 'rb');
        $fs = new Filesystem();
# 改进用户体验

        for ($i = 0; $i < $fileSize; $i += $chunkSize) {
            $chunkName = pathinfo($filePath, PATHINFO_FILENAME) . '_' . $i . pathinfo($filePath, PATHINFO_EXTENSION);
            $fs->copy($filePath, $chunkName, true);
            ftruncate($fileHandle, $i);
            fclose($fileHandle);
            $fileHandle = fopen($chunkName, 'ab');
            fwrite($fileHandle, fread($filePath, $chunkSize));
            fclose($fileHandle);
            $io->note("Chunk {$i} created: {$chunkName}");
        }

        return Command::SUCCESS;
    }

    /**
     * Merges the chunks back into the original file.
     *
# TODO: 优化性能
     * @param string $filePath
# 优化算法效率
     * @param SymfonyStyle $io
     * @return int
     */
    private function mergeFiles(string $filePath, SymfonyStyle $io): int
    {
        $fs = new Filesystem();
# TODO: 优化性能
        $finder = new Finder();
        $finder->in(dirname($filePath))->depth(0)->name('*');

        foreach ($finder as $file) {
            if ($file->getRelativePathname() !== pathinfo($filePath, PATHINFO_BASENAME)) {
                $fs->appendToFile($filePath, $fs->read($file->getRealPath()));
                $io->note("Merged file: {$file->getRelativePathname()}");
                $fs->remove($file->getRealPath());
            }
        }
# 改进用户体验

        return Command::SUCCESS;
    }
}

$application = new Application();
$application->add(new FileSplitMergeTool());
# TODO: 优化性能
$application->run();
