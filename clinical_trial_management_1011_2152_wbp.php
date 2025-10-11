<?php
// 代码生成时间: 2025-10-11 21:52:53
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ClinicalTrial;
use App\Form\ClinicalTrialType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

// ClinicalTrialController class responsible for handling clinical trial management
class ClinicalTrialController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Lists all clinical trials.
     *
   * @Route("/clinical-trials", name="list_clinical_trials")
     */
    public function list(): Response
    {
        try {
            $clinicalTrials = $this->entityManager->getRepository(ClinicalTrial::class)->findAll();
            return $this->render('clinical_trials/list.html.twig', ['clinicalTrials' => $clinicalTrials]);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Creates a new clinical trial.
     *
     * @Route("/clinical-trials/new", name="new_clinical_trial")
     */
    public function new(Request $request): Response
    {
        $clinicalTrial = new ClinicalTrial();
        $form = $this->createForm(ClinicalTrialType::class, $clinicalTrial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->entityManager->persist($clinicalTrial);
                $this->entityManager->flush();
                return new RedirectResponse($this->generateUrl('list_clinical_trials'));
            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->render('clinical_trials/new.html.twig', [
            'clinicalTrial' => $clinicalTrial,
            'form' => $form->createView(),
        ]);
    }

    // Additional methods like edit, delete, and view can be implemented similarly.
}

// ClinicalTrial entity representing the structure of a clinical trial.
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClinicalTrialRepository")
 */
class ClinicalTrial
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    // Additional properties like description, startDate, endDate, etc., can be added here.

    // Getters and setters for properties.
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    // Additional getters and setters.
}

// ClinicalTrialType form for creating and editing clinical trials.
namespace App\Form;

use App\Entity\ClinicalTrial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClinicalTrialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr' => ['class' => 'form-control'],
            ])
            // Additional fields like description, start date, etc., can be added here.
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClinicalTrial::class,
        ]);
    }
}
