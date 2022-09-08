<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{

    #[Route('/transaction', name: 'transaction_list', methods: 'GET')]
    public function getTransactions(TransactionRepository $transactionRepository): Response
    {
        $transactionsArray = $transactionRepository->findAll();

        $status = empty($transactionsArray) ? 404 : 204;

        $response = new JsonResponse($transactionsArray);
        $response->headers->set('Content-Type', 'application/json', $status);

        return $response;
    }
}
