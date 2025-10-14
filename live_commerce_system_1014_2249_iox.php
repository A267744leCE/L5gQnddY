<?php
// 代码生成时间: 2025-10-14 22:49:40
// 使用命名空间引入Symfony框架的相关组件
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

// 定义控制器类
class LiveCommerceController extends AbstractController
{
    // 构造函数注入EntityManager和ProductRepository
    public function __construct(private EntityManagerInterface $entityManager, private ProductRepository $productRepository)
    {
    }

    /**
     * @Route("/live/commerce", name="live_commerce", methods={"GET"})
     * 列出所有产品
     */
    public function index(): Response
    {
        try {
            $products = $this->productRepository->findAll();
            return new JsonResponse($products);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/live/commerce/product/{id}", name="product_detail", methods={"GET"}, requirements={"id"="\d+"})
     * 获取单个产品详情
     */
    public function show(int $id): Response
    {
        try {
            $product = $this->productRepository->find($id);
            if (!$product) {
                return new JsonResponse(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }
            return new JsonResponse($product);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/live/commerce/product", name="product_create", methods={"POST"})
     * 创建新产品
     */
    public function create(Request $request, FormFactoryInterface $formFactory): Response
    {
        try {
            $product = new Product();
            $form = $formFactory->createNamed('product', ProductType::class, $product);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($product);
                $this->entityManager->flush();
                return new JsonResponse($product);
            } else {
                return new JsonResponse($form->getErrors(), Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

// 定义实体类Product
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private string $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotBlank
     * @Assert\GreaterThan(0)
     */
    private float $price;

    // Getters and setters...
}

// 定义产品表单类型
namespace App\Form;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Product Name'])
            ->add('price', MoneyType::class, ['label' => 'Price']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

// 定义产品仓库类
namespace App\Repository;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // Custom repository methods...
}
