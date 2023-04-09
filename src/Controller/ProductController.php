<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Produit;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductType;

class ProductController extends AbstractController
{
    /**
     * @Route("/produits",name="produits")
     */
    public function index(ProduitRepository $repo)
    {

        $produit = $repo->findAll();
        return $this->render('Twig/index.html.twig', [
            'controller_name' => 'ProductController',
            'produit' => $produit
        ]);
    }

    /**
     * @Route("/categories",name="categories")
     */
    public function category(CategoryRepository $repo)
    {

        $category = $repo->findAll();
        return $this->render('Twig/categories.html.twig', [
            'controller_name' => 'ProductController',
            'category' => $category
        ]);
    }
    /**
     *@Route("/",name="home") 
     */
    public function home()
    {
        return $this->render('Twig/home.html.twig', [
            'title' => 'Bienvenue',
            'age' => 31
        ]);
    }
    /**
     * @Route("/produits/new",name="produits_create")
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $produit = new produit();
        // $form=$this->createFormBuilder($produit)
        //            ->add('title')
        //            ->add('content')
        //            ->add('image')
        //            ->getForm();
        $form = $this->createForm(ProductType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $produit->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($produit);
            $manager->flush();

            // return $this->redirectToRoute('produits_show', ['id' => $produit->getId()]);
        }


        return $this->render('Twig/create.html.twig', [
            'formproducts' => $form->createView()
        ]);
    }
    /**
     * @Route("/produits/{id}",name="produits_show")
     */
    public function show($id, ProduitRepository $repo)
    {

        $produit = $repo->find($id);

        return $this->render('Twig/show.html.twig', [
            'produit' => $produit
        ]);
    }


    /**
     * @Route("/create_category",name="create_category")
     */
    public function create_category(Request $request, EntityManagerInterface $manager)
    {
        $category = new Category();
        // $form=$this->createFormBuilder($produit)
        //            ->add('title')
        //            ->add('content')
        //            ->add('image')
        //            ->getForm();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            $this->render('Twig/categories.html.twig', [
                'category' =>   $category
            ]);

            return $this->redirectToRoute('produits_show');
        }


        return $this->render('Twig/create_category.html.twig', [
            'formCategory' => $form->createView()
        ]);
    }
}
