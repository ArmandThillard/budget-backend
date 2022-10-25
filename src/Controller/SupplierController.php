<?php

namespace App\Controller;

use App\Repository\SupplierRepository;
use PHPUnit\TextUI\XmlConfiguration\Groups;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SupplierController extends AbstractController
{

    #[Route('/supplier', name: 'supplier_list', methods: 'GET')]
    public function getTransactions(SupplierRepository $supplierRepository, NormalizerInterface $normalizer): Response
    {
        $suppliers = $supplierRepository->findAll();

        $suppliersNormalized = $normalizer->normalize($suppliers, 'json', ["groups" => "show_supplier"]);

        $json = json_encode($suppliersNormalized);

        $status = empty($suppliers) ? 204 : 200;

        $response = new Response($json, $status, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }
}
