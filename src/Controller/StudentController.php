<?php

namespace Arcsym\RestApiSymfony\Controller;

use Arcsym\RestApiSymfony\Form\StudentType;
use Arcsym\RestApiSymfony\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Here are all API ENDPOINTS for student resource.
 * This API is a RESTful API because it allow us to get, create, update and delete objects
 * with the HTTP verbs GET, POST, PUT / PATCH and DELETE.
 * Content type is JSON for both (client and server). So, Content-Type header must be application/json.
 *
 * @Route("/api/v1.0")
 */
class StudentController extends AbstractController
{
  /**
   * @var EntityManagerInterface
   */
  private EntityManagerInterface $em;

  /**
   * @var TranslatorInterface
   */
  private TranslatorInterface $translator;


  /**
   * @param EntityManagerInterface $em
   * @param TranslatorInterface $translator
   */
  public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
  {
    $this->em = $em;
    $this->translator = $translator;
  }

  /**
   * @param StudentRepository $studentRepository
   * @param Request $request
   * @return JsonResponse
   *
   * @Route("/students", name="api_students_list", methods={"GET"})
   */
  public function list(StudentRepository $studentRepository, Request $request): JsonResponse
  {
    $response = $this->checkJsonRequest($request);

    if(!empty($response)) {
      return $this->json($response, $response['statusCode']);
    }

    $response = [
      'statusCode' => Response::HTTP_OK,
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
   * @param Request $request
   * @return JsonResponse
   *
   * @Route("/students/{id}", name="api_students_show", methods={"GET"})
   */
  public function show(int $id, StudentRepository $studentRepository, Request $request): JsonResponse
  {
    $response = $this->checkJsonRequest($request);

    if(!empty($response)) {
      return $this->json($response, $response['statusCode']);
    }

    $student = $studentRepository->find($id);

    if(empty($student)) {
      $response = [
        'statusCode' => Response::HTTP_NOT_FOUND,
        'message' => [$this->translator->trans('message.not_found', ['%name%' => 'Student'])]
      ];

      return $this->json($response, $response['statusCode']);
    }

    $response = [
      'statusCode' => Response::HTTP_OK,
      'message' => [],
      'data' => $student->objectToArray(),
    ];

    return $this->json($response, $response['statusCode']);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   *
   * @Route("/students", name="api_students_create", methods={"POST"})
   */
  public function create(Request $request)
  {
    $response = $this->checkJsonRequest($request);

    if(!empty($response)) {
      return $this->json($response, $response['statusCode']);
    }

    $data = \json_decode($request->getContent(), true);
    $form = $this->createForm(StudentType::class);

    try {
      $form->submit($data);
    } catch (\Exception $e) {}

    if(!$form->isSubmitted() || !$form->isValid()) {
      $errors = [];

      foreach ($form->getErrors(true) as $error) {
        $message = $this->translator->trans($error->getMessage());

        if(!in_array($message, $errors)) {
          $errors[] = $message;
        }
      }

      if(empty($errors)) {
        $errors[] = 'Data not valid';
      }

      $response = [
        'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
        'message' => $errors
      ];

      return $this->json($response, $response['statusCode']);
    }

    $student = $form->getData();
    $em = $this->getDoctrine()->getManager();
    $em->persist($student);
    $em->flush();

    $response = [
      'statusCode' => Response::HTTP_CREATED,
      'message' => [$this->translator->trans('message.created', ['%name%' => 'Student'])],
      'data' => $student->objectToArray(),
    ];

    return $this->json($response, $response['statusCode']);
  }

  /**
   * @param int $id
   * @param StudentRepository $studentRepository
   * @param Request $request
   * @return JsonResponse
   *
   * @Route("/students/{id}", name="api_students_edit", methods={"PUT"})
   */
  public function edit(int $id, StudentRepository $studentRepository, Request $request)
  {
    $response = $this->checkJsonRequest($request);

    if(!empty($response)) {
      return $this->json($response, $response['statusCode']);
    }

    $student = $studentRepository->find($id);

    if(empty($student)) {
      $response = [
        'statusCode' => Response::HTTP_NOT_FOUND,
        'message' => [$this->translator->trans('message.not_found', ['%name%' => 'Student'])]
      ];

      return $this->json($response, $response['statusCode']);
    }

    $data = \json_decode($request->getContent(), true);
    $form = $this->createForm(StudentType::class, $student);

    try {
      $form->submit($data);
    } catch (\Exception $e) {}

    if(!$form->isSubmitted() || !$form->isValid()) {
      $errors = [];

      foreach ($form->getErrors(true) as $error) {
        $message = $this->translator->trans($error->getMessage());

        if(!in_array($message, $errors)) {
          $errors[] = $message;
        }
      }

      if(empty($errors)) {
        $errors[] = $this->translator->trans('invalid.data');
      }

      $response = [
        'statusCode' => Response::HTTP_UNPROCESSABLE_ENTITY,
        'message' => $errors
      ];

      return $this->json($response, $response['statusCode']);
    }

    $this->em->persist($student);
    $this->em->flush();

    $response = [
      'statusCode' => Response::HTTP_OK,
      'message' => [$this->translator->trans('message.updated', ['%name%' => 'Student'])],
      'data' => $student->objectToArray(),
    ];

    return $this->json($response, $response['statusCode']);
  }

  /**
   * @param int $id
   * @param StudentRepository $studentRepository
   * @param Request $request
   * @return JsonResponse
   *
   * @Route("/students/{id}", name="api_students_delete", methods={"DELETE"})
   */
  public function delete(int $id, StudentRepository $studentRepository, Request $request) {
    $response = $this->checkJsonRequest($request);

    if(!empty($response)) {
      return $this->json($response, $response['statusCode']);
    }

    $student = $studentRepository->find($id);

    if(empty($student)) {
      $response = [
        'statusCode' => Response::HTTP_NOT_FOUND,
        'message' => [$this->translator->trans('message.not_found', ['%name%' => 'Student'])],
      ];

      return $this->json($response, $response['statusCode']);
    }

    $this->em->remove($student);
    $this->em->flush();

    $response = [
      'statusCode' => Response::HTTP_OK,
      'message' => [$this->translator->trans('message.deleted', ['%name%' => 'Student'])]
    ];

    return $this->json($response, $response['statusCode']);
  }

  /**
   * @param Request $request
   * @return array
   */
  private function checkJsonRequest(Request $request): array
  {
    $response = [];

    if(!empty($request->getContent())) {
      \json_decode($request->getContent(), true);
    }

    switch (true) {
      case $request->getContentType() != 'json':
        $response = [
          'statusCode' => Response::HTTP_NOT_ACCEPTABLE,
          'message' => $this->translator->trans('message.json_content'),
        ];
        break;
      case json_last_error():
        $response = [
          'statusCode' => Response::HTTP_BAD_REQUEST,
          'message' => $this->translator->trans('message.json'),
        ];
        break;
    }

    return $response;
  }
}
