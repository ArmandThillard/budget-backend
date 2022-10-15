<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use ImportFileService;
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

        $filePath = $projectDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'csv' . DIRECTORY_SEPARATOR . $filename;

        file_put_contents($filePath, $data);

        $status = is_file($filePath) ? 201 : 400;

        $fileEntity = new File();

        $fileEntity->setName($filename);
        $fileEntity->setPath($filePath);
        $fileEntity->setHash(md5($data));

        $fileRepository->add($fileEntity, true);

        $sqlFolder = $projectDir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Sql' . DIRECTORY_SEPARATOR;

        ImportFileService::loadData($filename, $fileEntity->getFileId());

        return new Response('File added', $status);
    }
}
