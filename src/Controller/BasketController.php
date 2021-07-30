<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\UserAdressListType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    /**
     * @Route("/basket", name="basket")
     */
    public function index(Request $request, ProductRepository $productRepository): Response
    {

        $request->getSession()->set('listBasket', []);

        $basket = $request->getSession()->get('listBasket');
        if (!$basket) {
            $request->getSession()->set('listBasket', []);
        }

        if ($request->request->get('product')) {
            if (!isset($basket[$request->request->get('product')])) {
                $basket[$request->request->get('product')] = [
                    'product' => $productRepository->findOneBy(
                        ['id' => $request->request->get('product')]
                    ),
                    'quantity' => $request->request->get('quantity')
                ];
            } else {
                $basket[$request->request->get('product')]['quantity'] =
                    (int) $basket[$request->request->get('product')]['quantity'] +
                    (int) $request->request->get('quantity');
            }
        }
            $request->getSession()->set('listBasket', $basket);

            $form= $this->createForm(UserAdressListType::class,[], [
                'action' => $this->generateUrl('invoice_index')
            ]);
            $form->handleRequest($request);

            return $this->render('basket/index.html.twig', [
                
                'listBasket' => $basket,
                'formUserAddress' =>$form->createView()
            ]);
        
    }
}
