<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MoviesController extends AbstractController
{
    private $em;
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    #[Route('/movies', methods:['GET'], name: 'get_allMovie')]
    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();
       
        return $this->render('movies/index.html.twig',[
            'movies' => $movies
        ]);
    }

    #[Route('/movies/create', name: 'create_movie')]
    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class,$movie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newMovie = $form->getData();

            $imagePath = $form->get('imagePath')->getData();

            if($imagePath){
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                try{
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',$newFileName
                    );

                }catch(FileException $e){
                    return new Response($e->getMessage());
                }
                $newMovie->setImagePath('/uploads/'. $newFileName);
            }

            $this->em->persist($newMovie);
            $this->em->flush();

            return $this->redirectToRoute('get_allMovie'); // mention the name

        }
       
        return $this->render('movies/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/movies/edit/{id}', name:'edit_movie')]
    public function edit($id,Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        $form = $this->createForm(MovieFormType::class,$movie);

        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();
        if($form->isSubmitted() && $form->isValid()){
            if($imagePath){
                // dd($this->getParameter('kernel.project_dir'));
                // dd($movie->getImagePath());
                if($movie->getImagePath() !==null){
                    if(!file_exists(
                        $this->getParameter('kernel.project_dir') . $movie->getImagePath()
                    ))
                    {
                        // dd("hello");
                        $this->GetParameter('kernel.project_dir').'/public/uploads';
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();
                        try{
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',$newFileName
                            );
        
                        }catch(FileException $e){
                            return new Response($e->getMessage());
                        }
                        $movie->setImagePath('/uploads/'. $newFileName);
                        $this->em->flush();

                        return $this->redirectToRoute('get_allMovie');
                    }
                }
            }else{
                $movie->setTitle($form->get('title')->getData());
                $movie->setDescription($form->get('description')->getData());
                $movie->setReleaseYear($form->get('releaseYear')->getData());
                
                $this->em->flush();
                return $this->redirectToRoute('get_allMovie'); // mention the name
            }
        }
       
        

        return $this->render('movies/edit.html.twig',[
            'movie' => $movie,
            'form' => $form->createView()
        ]);

    }
    
    #[Route('/movies/delete/{id}', methods:['GET', 'DELETE'], name:'delete')]
    public function delete($id): Response
    {
        $movie = $this->movieRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();

        return $this->redirectToRoute('get_allMovie');
    }

    #[Route('/movies/{id}', methods:['GET'], name: 'show_movie')]
    public function show($id): Response
    {
        $movie = $this->movieRepository->find($id);
       
        return $this->render('movies/show.html.twig',[
            'movie' => $movie
        ]);
    }

}
