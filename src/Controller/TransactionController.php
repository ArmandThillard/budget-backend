<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class TransactionController extends AbstractController
{

    #[Route('/transaction', name: 'transaction_list', methods: 'GET')]
    public function getTransactions(TransactionRepository $transactionRepository, SerializerInterface $serializer): Response
    {
        $transactions = $transactionRepository->findAll();

        $json = $serializer->serialize($transactions, 'json', ["groups" => "show_transaction"]);
        $status = 200;

        $response = new Response($json, $status, [
            "Content-Type" => "application/json"
        ]);

        return $response;
    }

    #[Route('/transaction/{id}', name: 'update_transaction', methods: ['PUT'])]
    public function updateTransaction(Request $request, TransactionRepository $transactionRepository, SerializerInterface $serializer, int $id): Response
    {
        $transaction = $transactionRepository->find($id);

        if (!$transaction) {
            throw new EntityNotFoundException(sprintf(
                'No transaction found with id "%s"',
                $id
            ));
        }

        $serializer->deserialize($request->getContent(), Transaction::class, 'json', [AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true, AbstractNormalizer::OBJECT_TO_POPULATE => $transaction]);

        $transactionRepository->update();

        return new Response('Transaction with id ' . $id . ' updated', 201);
    }
}
