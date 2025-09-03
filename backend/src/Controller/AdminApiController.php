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
        // 1. Get the data from the incoming request
        // The data is expected to be in JSON format in the request body
        $data = json_decode($request->getContent());
        // 2. Basic Validation (we can add more robust validation later)
        if (empty($data->companyName) || empty($data->jobTitle) || empty($data->startDate)) {
            return $this->json(['error' => 'Missing required fields'], 400); // 400 means Bad Request
        }

        // 3. Create a new Experience entity object
        $experience = new Experience();
        $experience->setCompanyName($data->companyName);
        $experience->setJobTitle($data->jobTitle);
        $experience->setDescription($data->description ?? null); // Use null if not provided

        // 4. Handle the dates
        // We need to convert the string date (e.g., "2022-01-30") into a PHP DateTime object
        $experience->setStartDate(new DateTime($data->startDate));
        if (!empty($data->endDate)) {
            $experience->setEndDate(new DateTime($data->endDate));
        } else {
            $experience->setEndDate(null);
        }

        // 5. Tell Doctrine to save the new Experience
        // ->persist() stages the object for saving.
        $entityManager->persist($experience);
        // ->flush() executes the query and saves everything to the database.
        $entityManager->flush();

        // 6. Return a success response
        // It's good practice to return the data of the newly created object.
        return $this->json([
            'message' => 'Experience created successfully!',
            'id' => $experience->getId(),
            'companyName' => $experience->getCompanyName(),
        ], 201); // 201 means "Created"
    }
}
