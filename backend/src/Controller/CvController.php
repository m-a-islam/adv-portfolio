<?php
// backend/src/Controller/CvController.php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class CvController extends AbstractController
{
    /**
     * This method fetches all experience entries from the database.
     * It will listen for GET requests at the URL /api/experience
     */
    #[Route('/api/experience', name: 'api_experience_get_all', methods: ['GET'])]
    public function getAllExperience(ExperienceRepository $experienceRepository): JsonResponse
    {
        // 1. Use the injected ExperienceRepository to find all records.
        // The repository is a special class Symfony creates for you to easily query a table.
        // ->findAll() is a built-in method that gets every row.
        $experiences = $experienceRepository->findAll();
        #dd($experiences);
        // 2. Return the data as a JSON response.
        // Symfony will automatically convert the array of Experience objects into a clean JSON array.
        // The 'groups' option is important for controlling which data gets exposed.
        return $this->json($experiences, 200, [], ['groups' => 'public']);
    }
}
