<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/file', name: 'upload_file', methods: 'POST')]
    public function uploadFile(Request $request, FileRepository $fileRepository): Response
    {
        $content = json_decode($request->getContent());
        $filename = $content->filename;
        $data = $content->data;

        $projectDir = $this->getParameter('kernel.project_dir');

        file_put_contents($projectDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . $filename, $data);
        // $status = is_file($file) ? 201 : 400;

        $fileEntity = new File();

        // $fileEntity->setName($file->originalName);
        // $fileEntity->setPath();
        return new Response('', 201);
    }
}
