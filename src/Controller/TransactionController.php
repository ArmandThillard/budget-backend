<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TransactionController extends AbstractController
{

    #[Route('/transaction', name: 'transaction_list', methods: 'GET')]
    public function getTransactions(TransactionRepository $transactionRepository, NormalizerInterface $normalizer): Response
    {
        $transactions = $transactionRepository->findAll();

        $suppliersNormalized = $normalizer->normalize($transactions, 'json', ["groups" => "show_transaction"]);

        $json = json_encode($suppliersNormalized);

        $status = empty($transactions) ? 204 : 200;

        $response = new Response($json, $status, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }
}
