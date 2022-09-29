<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CategoryController extends AbstractController
{

    #[Route('/category', name: 'category_list', methods: 'GET')]
    public function getCategories(CategoryRepository $categoryRepository, NormalizerInterface $normalizer): Response
    {
        $categories = $categoryRepository->findAll();

        $categoriesNormalized = $normalizer->normalize($categories, 'json', ["groups" => "show_category"]);

        $json = json_encode($categoriesNormalized);

        $status = empty($categories) ? 204 : 200;

        $response = new Response($json, $status, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }
}
