<?php

namespace App\Controller;

use App\Entity\ImageReviews;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReviewController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(ReviewRepository $reviewRepository)
    {
        if (isset($_GET['date1'])) {
            $reviews = $reviewRepository->orderByDateDESC();
            $name = "Date - plus récent";
        }
        elseif (isset($_GET['date2'])) {
            $reviews = $reviewRepository->orderByDateASC();
            $name = "Date - plus ancien";
        }
        elseif (isset($_GET['rating1'])) {
            $reviews = $reviewRepository->orderByRatingDESC();
            $name = "Note - plus haute au plus bas";
        }
        elseif (isset($_GET['rating2'])) {
            $reviews = $reviewRepository->orderByRatingASC();
            $name = "Note - plus basse au plus haut";
        }
        else {
            $reviews = $reviewRepository->findAll();
        }

        return $this->render('home/index.html.twig', [
            'reviews' => $reviews,
            'name' => $name ?? null,
        ]);
    }

    /**
     * @Route("/review", name="review")
     */
    public function review(Request $request, SluggerInterface $slugger)
    {
        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review = $form->getData();

            //! Gérer en GMT +2
            $review->setCreatedAt(new \DateTime());

            $uploadedImages = $form['imageReviews']->getData();

            if ($uploadedImages) {

                foreach ($uploadedImages as $image) {

                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
    
                    $safeFilename = $slugger->slug($originalFilename);
    
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
    
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    
                    $img = new ImageReviews();
                    $img->setName($newFilename);
                    $review->addImageReview($img);
                }
            }

            $this->addFlash('success', 'Votre avis a bien été pris en compte.');

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('review/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
