<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Form\EditProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $products= $repo->findAll();
    #$products=["article1","article2","article2"];
    return $this->render('product/index.html.twig', ['products' => $products]);
    }



     /**
     * @Route("/product/add2", name="add2")
     * 
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $prod = new Product();
        $form = $this->createForm(ProductType::class, $prod); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //$prod = $form->getData();
             // ... perform some action, such as saving the task to the database
             // for example, if Task is a Doctrine entity, save it!
             $manager = $this->getDoctrine()->getEntityManager();
             $manager->persist($prod);
             $manager->flush();
             
            return $this->redirectToRoute('product');
        }
        
        return $this->render('product\new.html.twig', ['formpro'=>$form->createView()]);
        

    }

    /**
     * @Route("/product/detail/{id}", name="detail")
     */
    public function detail($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $products = $repo->find($id);
        return $this->render('product/detail.html.twig', ['products' => $products]);
    }

     /**
     * @Route("/product/delete/{id}", name="delete")
     */
    public function delete($id): Response
    {
        $repo = $this->getDoctrine()->getRepository(Product::class);
        $products= $repo->find($id);
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($products);
        $manager->flush();
        #return $this->render('prodact/index.html.twig', ['products' => $products]);
        return new Response("suppression validée");
    }

    /**
     * @Route("/product/add", name="add")
     */
    public function add(): Response
    {
        $manager = $this->getDoctrine()->getManager();
 
        $product = new Product();
        $product->setlib("lib2")
        ->setprix(500)
        ->setdes("test description de l'article ")
        ->setimg("http://placehold.it/350*150");
        $manager->persist($product);
        $manager->flush();
        return new Response("ajout validé de l'article identifié par:".$product->getId());
    }

    /**
     * @Route("/product/detail/update/{id}", name="update")
     */
    public function edit(Request $request, $id): Response
    {
        $prod = new Product();
        $form = $this->createForm(ProductType::class, $prod);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prod);
            $entityManager->flush();

            return new Response("mettre à jour validé.");
        }
        return $this->renderForm('product\update.html.twig', ['formupdate'=>$form, ]);
    }

}
