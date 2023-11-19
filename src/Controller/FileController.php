<?php

namespace App\Controller;

use App\Entity\File;
use App\Repository\FileRepository;
use DateTime;
use ErrorException;
use ImportFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class FileController extends AbstractController
{

    #[Route('/api/file', name: 'file_list', methods: 'GET')]
    public function getFiles(
        FileRepository $fileRepository,
        NormalizerInterface $normalizer,
        SerializerInterface $serializer
    ): Response {
        $files = $fileRepository->findAll();

        $json = $serializer->serialize($files, 'json', ["groups" => "show_transaction"]);
        $status = 200;

        return new Response($json, $status, [
            "Content-Type" => "application/json"
        ]);
    }

    #[Route('/api/file', name: 'upload_file', methods: 'POST')]
    public function uploadFile(Request $request, FileRepository $fileRepository): Response
    {
        $content = json_decode($request->getContent());
        $filename = $content->filename;
        $importDate = new DateTime($content->importDate);
        $month = $content->month;
        $income = $content->income;
        $data = $content->data;

        $projectDir = $this->getParameter('kernel.project_dir');

        $filePath = $projectDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'csv' . DIRECTORY_SEPARATOR . $filename;

        file_put_contents($filePath, $data);

        $status = is_file($filePath) ? 201 : 400;

        $fileEntity = new File();

        $fileEntity->setName($filename);
        $fileEntity->setPath($filePath);
        $fileEntity->setHash(md5($data));
        $fileEntity->setImportDate($importDate);
        $fileEntity->setMonth($month);
        $fileEntity->setIncome($income);

        $fileRepository->add($fileEntity, true);

        $fileId = $fileEntity->getFileId();

        $message = "File added with id $fileId";
        try {
            ImportFileService::loadData($filename, $fileId);
        } catch (ErrorException $e) {
            $status = 500;
            $message = $e;
            $fileRepository->remove($fileEntity, true);
        }

        return new Response($message, $status);
    }
}
