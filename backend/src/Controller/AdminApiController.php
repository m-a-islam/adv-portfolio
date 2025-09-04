<?php
// backend/src/Controller/AdminApiController.php

namespace App\Controller;

use App\Entity\Experience;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class AdminApiController extends AbstractController
{
    /**
     * This method will handle creating a new Experience entry.
     * It will listen for POST requests at the URL /api/admin/experience
     */
    #[Route('/api/admin/experience', name: 'api_admin_experience_create', methods: ['POST'])]
    public function createExperience(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        $data = json_decode($request->getContent());

        if (empty($data->companyName) || empty($data->jobTitle) || empty($data->startDate)) {
            return $this->json(['error' => 'Missing required fields'], 400); // 400 means Bad Request
        }

        $experience = new Experience();
        $experience->setCompanyName($data->companyName);
        $experience->setJobTitle($data->jobTitle);
        $experience->setDescription($data->description ?? null); // Use null if not provided

        $experience->setStartDate(new DateTime($data->startDate));
        if (!empty($data->endDate)) {
            $experience->setEndDate(new DateTime($data->endDate));
        } else {
            $experience->setEndDate(null);
        }

        $entityManager->persist($experience);
        $entityManager->flush();

        return $this->json([
            'message' => 'Experience created successfully!',
            'id' => $experience->getId(),
            'companyName' => $experience->getCompanyName(),
        ], 201);
    }

    #[Route('/api/admin/experience/{id}', name: 'api_admin_experience_delete', methods: ['DELETE'])]
    public function deleteExperience(Experience $experience, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($experience);

        $entityManager->flush();

        return new JsonResponse(null, 204);
    }

    /**
     * @throws \Exception
     */
    #[Route('/api/admin/experience/{id}', name: 'api_admin_experience_update', methods: ['PUT'])]
    public function updateExperience(Experience $experience, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent());

        if (empty($data->companyName) || empty($data->jobTitle) || empty($data->startDate)) {
            return $this->json(['error' => 'Missing required fields'], 400);
        }

        $experience->setCompanyName($data->companyName);
        $experience->setJobTitle($data->jobTitle);
        $experience->setDescription($data->description ?? null);
        $experience->setStartDate(new \DateTime($data->startDate));
        $experience->setEndDate(!empty($data->endDate) ? new \DateTime($data->endDate) : null);


        $entityManager->flush();

        return $this->json([
            'message' => 'Experience updated successfully!',
            'id' => $experience->getId(),
        ]);
    }
}
