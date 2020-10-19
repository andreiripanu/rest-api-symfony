<?php

namespace Arcsym\RestApiSymfony\Controller;

use Arcsym\RestApiSymfony\Form\StudentType;
use Arcsym\RestApiSymfony\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class StudentController.
 *
 * @Route("/api/student", name="api_student")
 */
class StudentController extends AbstractController
{
  /**
   * @var EntityManagerInterface
   */
  private EntityManagerInterface $em;

  /**
   * StudentController constructor.
   *
   * @param EntityManagerInterface $em
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  /**
   * @param StudentRepository $studentRepository
   * @return JsonResponse
   *
   * @Route("/all", name="api_student_all", methods={"GET"})
   */
  public function all(StudentRepository $studentRepository): JsonResponse
  {
    $response = [
      'statusCode' => 200,
      'message' => [],
      'data' => []
    ];

    $students = $studentRepository->findBy([], ['id' => 'DESC']);

    foreach ($students as $s) {
      $response['data'][] = $s->objectToArray();
    }

    return $this->json($response, $response['statusCode']);
  }

  /**
   * @param int $id
   * @param StudentRepository $studentRepository
   * @return JsonResponse
   *
   * @Route("/{id}/show", name="api_student_show", methods={"GET"})
   */
  public function show(int $id, StudentRepository $studentRepository): JsonResponse
  {
    $student = $studentRepository->find($id);

    if(empty($student)) {
      $response = [
        'statusCode' => 404,
        'message' => ['Student not found'],
        'data' => []
      ];

      return $this->json($response, $response['statusCode']);
    }

    $response = [
      'statusCode' => 200,
      'message' => [],
      'data' => $student->objectToArray(),
    ];

    return $this->json($response, $response['statusCode']);
  }

  /**
   * @Route("/create", name="api_student_create", methods={"POST"})
   */
  public function create(Request $request, TranslatorInterface $translator)
  {
    $data = \json_decode($request->getContent(), true);
    $form = $this->createForm(StudentType::class);

    try {
      $form->submit($data);
    } catch (\Exception $e) {}

    if($form->isSubmitted() && $form->isValid()) {
      $student = $form->getData();
      $em = $this->getDoctrine()->getManager();
      $em->persist($student);
      $em->flush();

      $response = [
        'statusCode' => 201,
        'message' => ['Student created successfully'],
        'data' => $student->objectToArray(),
      ];
    } else {
      $errors = [];

      foreach ($form->getErrors(true) as $error) {
        $message = $translator->trans($error->getMessage());

        if(!in_array($message, $errors)) {
          $errors[] = $message;
        }
      }

      $response = [
        'statusCode' => 422,
        'message' => $errors
      ];
    }

    return $this->json($response, $response['statusCode']);
  }

  /**
   * @param int $id
   * @param StudentRepository $studentRepository
   * @return JsonResponse
   *
   * @Route("/{id}/edit", name="api_student_edit", methods={"PUT"})
   */
  public function edit(int $id, StudentRepository $studentRepository, Request $request, TranslatorInterface $translator)
  {
    $student = $studentRepository->find($id);

    if(empty($student)) {
      $response = [
        'statusCode' => 404,
        'message' => ['Student not found']
      ];

      return $this->json($response, $response['statusCode']);
    }

    $data = \json_decode($request->getContent(), true);
    $form = $this->createForm(StudentType::class, $student);

    try {
      $form->submit($data);
    } catch (\Exception $e) {}

    if($form->isSubmitted() && $form->isValid()) {
      $this->em->persist($student);
      $this->em->flush();

      $response = [
        'statusCode' => 200,
        'message' => ['Student updated successfully'],
        'data' => $student->objectToArray(),
      ];
    } else {
      $errors = [];

      foreach ($form->getErrors(true) as $error) {
        $message = $translator->trans($error->getMessage());

        if(!in_array($message, $errors)) {
          $errors[] = $message;
        }
      }

      if(empty($errors)) {
        $errors[] = 'Data not valid';
      }

      $response = [
        'statusCode' => 422,
        'message' => $errors
      ];
    }

    return $this->json($response, $response['statusCode']);
  }

  /**
   * @param int $id
   * @param StudentRepository $studentRepository
   * @return JsonResponse
   *
   * @Route("/{id}/delete", name="api_student_delete", methods={"DELETE"})
   */
  public function delete(int $id, StudentRepository $studentRepository) {
    $student = $studentRepository->find($id);

    if(empty($student)) {
      $response = [
        'statusCode' => 404,
        'message' => ['Student not found']
      ];

      return $this->json($response, $response['statusCode']);
    }

    $this->em->remove($student);
    $this->em->flush();

    $response = [
      'statusCode' => 200,
      'message' => ['Student deleted successfully']
    ];

    return $this->json($response, $response['statusCode']);
  }
}
