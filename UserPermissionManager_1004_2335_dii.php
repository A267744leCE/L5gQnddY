<?php
// 代码生成时间: 2025-10-04 23:35:50
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

// 用户权限管理类
class UserPermissionManager {
    private $container;

    // 构造函数
    public function __construct(ContainerBuilder $container) {
        $this->container = $container;
    }

    // 添加权限
    public function addPermission($userId, $permission) {
        // 检查参数是否有效
        if (empty($userId) || empty($permission)) {
            throw new \u0022InvalidArgumentException\u0022('Invalid user ID or permission');
        }

        // 从容器中获取权限存储服务
        $permissionStorage = $this->container->get('permission_storage');

        // 添加权限
        if (!$permissionStorage->add($userId, $permission)) {
            throw new \u0022RuntimeException\u0022('Failed to add permission');
        }

        return true;
    }

    // 移除权限
    public function removePermission($userId, $permission) {
        // 检查参数是否有效
        if (empty($userId) || empty($permission)) {
            throw new \u0022InvalidArgumentException\u0022('Invalid user ID or permission');
        }

        // 从容器中获取权限存储服务
        $permissionStorage = $this->container->get('permission_storage');

        // 移除权限
        if (!$permissionStorage->remove($userId, $permission)) {
            throw new \u0022RuntimeException\u0022('Failed to remove permission');
        }

        return true;
    }

    // 获取用户权限
    public function getPermissions($userId) {
        // 检查参数是否有效
        if (empty($userId)) {
            throw new \u0022InvalidArgumentException\u0022('Invalid user ID');
        }

        // 从容器中获取权限存储服务
        $permissionStorage = $this->container->get('permission_storage');

        // 获取权限
        return $permissionStorage->get($userId);
    }
}

// 权限存储接口
interface PermissionStorageInterface {
    public function add($userId, $permission);
    public function remove($userId, $permission);
    public function get($userId);
}

// 权限存储服务类
class PermissionStorageService implements PermissionStorageInterface {
    private $permissions = [];

    // 添加权限
    public function add($userId, $permission) {
        if (!isset($this->permissions[$userId])) {
            $this->permissions[$userId] = [];
        }

        $this->permissions[$userId][] = $permission;
        return true;
    }

    // 移除权限
    public function remove($userId, $permission) {
        if (isset($this->permissions[$userId]) && in_array($permission, $this->permissions[$userId])) {
            $index = array_search($permission, $this->permissions[$userId]);
            unset($this->permissions[$userId][$index]);
            return true;
        }

        return false;
    }

    // 获取用户权限
    public function get($userId) {
        if (isset($this->permissions[$userId])) {
            return $this->permissions[$userId];
        }

        return [];
    }
}

// 容器配置
$container = new ContainerBuilder();

// 定义权限存储服务
$container->register('permission_storage', PermissionStorageService::class)
    ->setAutowired(true);

// 定义用户权限管理服务
$container->register(UserPermissionManager::class)
    ->setAutowired(true)
    ->addArgument(new Reference('permission_storage'));

// 编译容器
$container->compile();

// 创建用户权限管理实例
$userManager = $container->get(UserPermissionManager::class);

// 示例用法
try {
    // 添加权限
    $userManager->addPermission(1, 'edit_article');

    // 获取权限
    $permissions = $userManager->getPermissions(1);
    print_r($permissions);

    // 移除权限
    $userManager->removePermission(1, 'edit_article');
} catch (Exception $e) {
    echo \u0022Error: \u0022 . $e->getMessage();
}
