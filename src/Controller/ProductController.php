<?php

namespace App\Controller;

use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;
use Twig\Environment;

class ProductController extends AbstractController
{
    #[Route('/form', name: 'app_form')]
    public function showForm(Environment $twig, Request $request, EntityManagerInterface $entityManager)
    {
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($product);
            $entityManager->flush();

            return new Response('Product number'. $product->getId().'created..');
        }
        return new Response($twig->render('product/showForm.html.twig', [
            'product_form'=>$form->createView()
        ]));
    }



    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/create_product', name: 'create_product')]
    public function createProduct(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setRef('AF45R');
        $product->setQuantity(20);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/index.html.twig',[
            'product' => $product,
        ]);

    }

    #[Route('/list_product', name: 'list_product')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $products = $doctrine->getRepository(Product::class)->findAll();
        return $this->render('product/list.html.twig',[
            'products' => $products,
        ]);

    }

    #[Route('/delete_product/{id}', name: 'delete_product')]
    public function delete_product(ManagerRegistry $doctrine,int $id): Response
    {
        $product = $doctrine->getRepository(Product::class)->find($id);
        $entityManager = $doctrine->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return new Response('Delete product with id '.$id);

    }

    #[Route('/edit_product/{id}', name: 'edit_product')]
    public function edit_product(ManagerRegistry $doctrine,int $id): Response
    {
        $product = $doctrine->getRepository(Product::class)->find($id);
        $entityManager = $doctrine->getManager();
        $product->setName('Fabien');
        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Edit product with id '.$id);

    }
}
