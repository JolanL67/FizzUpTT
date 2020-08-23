<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;

class ReviewController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(ReviewRepository $reviewRepository)
    {
        $reviews = $reviewRepository->findAll();

        return $this->render('home/index.html.twig', [
            'reviews' => $reviews
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

            $uploadedImage = $form['image']->getData();

            if ($uploadedImage) {
                $originalFilename = pathinfo($uploadedImage->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFilename = $slugger->slug($originalFilename);

                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedImage->guessExtension();

                $uploadedImage->move(
                    $this->getParameter('avatar_directory'),
                    $newFilename
                );

                $review->setImage($newFilename);
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
