<?php
// 代码生成时间: 2025-10-18 11:51:21
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class VersionControl
 * Simple version control system using Symfony components
 */
class VersionControl
{
    private Filesystem $filesystem;
    private string $repositoryPath;
    private string $currentBranch;
    private array $branches;
    private array $commits;
    private array $tags;

    public function __construct(string $repositoryPath)
    {
        $this->filesystem = new Filesystem();
        $this->repositoryPath = rtrim($repositoryPath, '/') . '/';
        $this->branches = $this->loadBranches();
        $this->currentBranch = $this->branches['current'] ?? 'master';
        $this->commits = $this->loadCommits();
        $this->tags = $this->loadTags();
    }

    // Load branches from a configuration file or database
    private function loadBranches(): array
    {
        $configPath = $this->repositoryPath . 'branches.yml';
        if (!$this->filesystem->exists($configPath)) {
            throw new \Exception('Branches configuration file not found.');
        }

        return Yaml::parseFile($configPath);
    }

    // Load commits history from a file or database
    private function loadCommits(): array
    {
        $configPath = $this->repositoryPath . 'commits.yml';
        if (!$this->filesystem->exists($configPath)) {
            throw new \Exception('Commits history file not found.');
        }

        return Yaml::parseFile($configPath);
    }

    // Load tags from a configuration file or database
    private function loadTags(): array
    {
        $configPath = $this->repositoryPath . 'tags.yml';
        if (!$this->filesystem->exists($configPath)) {
            throw new \Exception('Tags configuration file not found.');
        }

        return Yaml::parseFile($configPath);
    }

    // Commit changes
    public function commit(string $message): void
    {
        $commitHash = uniqid('commit-', true);
        $commitPath = $this->repositoryPath . 'commits/' . $commitHash . '.yml';

        $commitData = [
            'hash' => $commitHash,
            'message' => $message,
            'branch' => $this->currentBranch,
            'timestamp' => time(),
        ];

        Yaml::dump($commitData, $commitPath, 4);

        $this->commits[] = $commitHash;
        $this->saveCommits();
    }

    // Save commits history
    private function saveCommits(): void
    {
        $configPath = $this->repositoryPath . 'commits.yml';
        Yaml::dump($this->commits, $configPath);
    }

    // List all branches
    public function listBranches(): array
    {
        return $this->branches;
    }

    // Switch to a different branch
    public function switchBranch(string $branchName): void
    {
        if (!isset($this->branches[$branchName])) {
            throw new \Exception("Branch '{$branchName}' not found.");
        }

        $this->currentBranch = $branchName;
    }

    // Tag the current commit
    public function tag(string $tagName): void
    {
        if (in_array($tagName, $this->tags)) {
            throw new \Exception("Tag '{$tagName}' already exists.");
        }

        $currentCommit = end($this->commits);
        $this->tags[] = [
            'name' => $tagName,
            'commit' => $currentCommit,
        ];
        $this->saveTags();
    }

    // Save tags
    private function saveTags(): void
    {
        $configPath = $this->repositoryPath . 'tags.yml';
        Yaml::dump($this->tags, $configPath);
    }
}

// Usage example
try {
    $vcs = new VersionControl('/path/to/repository');
    $vcs->commit('Initial commit');
    $vcs->switchBranch('feature');
    $vcs->tag('v1.0');
    print_r($vcs->listBranches());
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
