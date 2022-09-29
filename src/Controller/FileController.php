<?php

namespace App\Controller;

use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FileController extends AbstractController
{

    #[Route('/file', name: 'file_list', methods: 'GET')]
    public function getFiles(FileRepository $fileRepository, NormalizerInterface $normalizer): Response
    {
        $files = $fileRepository->findAll();

        $filesNormalized = $normalizer->normalize($files, 'json', ["groups" => "show_file"]);

        $json = json_encode($filesNormalized);

        $status = empty($files) ? 204 : 200;

        $response = new Response($json, $status, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }
}
