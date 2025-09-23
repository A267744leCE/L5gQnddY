<?php
// 代码生成时间: 2025-09-23 19:45:22
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

// ProcessManager 类用于管理进程
class ProcessManager {
    // 启动一个新的进程
    public function startProcess(string $command): ?Process {
        try {
            // 创建一个新的进程
            $process = new Process(escapeshellcmd($command));
            // 启动进程
            $process->start();
            // 检查进程是否成功启动
            if (!$process->isStarted()) {
                throw new \Exception('Process failed to start.');
            }
            return $process;
        } catch (\Exception $e) {
            // 错误处理
            error_log($e->getMessage());
            return null;
        }
    }

    // 检查进程是否正在运行
    public function isRunning(Process $process): bool {
        return $process->isRunning();
    }

    // 终止一个进程
    public function terminateProcess(Process $process): void {
        try {
            // 终止进程
            $process->stop();
        } catch (ProcessFailedException $e) {
            // 错误处理
            error_log($e->getMessage());
        }
    }

    // 获取进程的输出
    public function getOutput(Process $process): string {
        return $process->getOutput();
    }

    // 获取进程的错误输出
    public function getErrorOutput(Process $process): string {
        return $process->getErrorOutput();
    }
}

// 使用示例
/**
 * Usage example
 */
$processManager = new ProcessManager();
$command = 'ls -la'; // 替换为实际的命令
$process = $processManager->startProcess($command);

if ($process !== null) {
    while ($processManager->isRunning($process)) {
        // 可以在此处检查进程输出或执行其他操作
        sleep(1); // 每秒检查一次
    }

    echo 'Process output: ' . $processManager->getOutput($process) . "\
";
    echo 'Process error output: ' . $processManager->getErrorOutput($process) . "\
";

    $processManager->terminateProcess($process);
} else {
    echo 'Failed to start process.';
}
