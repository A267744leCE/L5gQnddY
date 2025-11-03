<?php
// 代码生成时间: 2025-11-03 11:16:59
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

// 控制器类，用于处理游戏数据分析
class GameDataAnalysisController extends AbstractController
{
    private $entityManager;
    private $serializer;
    private $validator;

    // 依赖注入
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    // 获取游戏数据
    /**
     * @Route("/game/data", methods={"GET"})
     */
    public function getGameData(): Response
    {
        try {
            // 查询数据库中的数据
            $gameData = $this->entityManager->getRepository(GameData::class)->findAll();

            // 序列化数据
            $jsonData = $this->serializer->serialize($gameData, 'json');

            // 返回响应
            return new Response($jsonData, Response::HTTP_OK, ["Content-Type" => "application/json"]);
        } catch (Exception $e) {
            // 错误处理
            return new Response(\$this->serializer->serialize(["error" => $e->getMessage()], 'json'), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-Type" => "application/json"]);
        }
    }

    // 存储游戏数据
    /**
     * @Route("/game/data", methods={"POST"})
     */
    public function storeGameData(Request $request): Response
    {
        try {
            // 获取请求数据
            $requestData = json_decode($request->getContent(), true);

            // 验证数据
            $errors = $this->validator->validate($requestData);
            if (count($errors) > 0) {
                // 如果存在验证错误，返回错误信息
                return new Response(\$this->serializer->serialize(["error" => "Invalid data"], 'json'), Response::HTTP_BAD_REQUEST, ["Content-Type" => "application/json"]);
            }

            // 创建游戏数据实体
            $gameData = new GameData();
            $gameData->setData($requestData);

            // 保存到数据库
            \$this->entityManager->persist($gameData);
            \$this->entityManager->flush();

            // 返回成功响应
            return new Response(\$this->serializer->serialize($gameData), Response::HTTP_CREATED, ["Content-Type" => "application/json"]);
        } catch (Exception $e) {
            // 错误处理
            return new Response(\$this->serializer->serialize(["error" => $e->getMessage()], 'json'), Response::HTTP_INTERNAL_SERVER_ERROR, ["Content-Type" => "application/json"]);
        }
    }
}

// 游戏数据实体
class GameData
{
    private $data;

    // 设置游戏数据
    public function setData(array \$data): void
    {
        \$this->data = \$data;
    }

    // 获取游戏数据
    public function getData(): array
    {
        return \$this->data;
    }
}
