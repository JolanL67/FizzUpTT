<?php

namespace App\Controller;

use App\Entity\ImageReviews;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ReviewController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     * @Route("/filter/{id}", name="home_filter", methods={"GET"})
     * 
     * Cette méthode représente la page d'accueil, ainsi que le traitement de tri par date/notes et le filtre par notes
     */
    public function home(ReviewRepository $reviewRepository, $id = '', NormalizerInterface $normalize)
    {
        // J'appelle la méthode du ReviewRepository afin de traiter le filtre par note et je le stocke dans une variable
        $filteredRatings = $reviewRepository->filterByRating($id);
        
        // Je transforme mon tableau d'objet en tableau associatif grâce au composant Serializer de Symfony
        $filteredArray = $normalize->normalize($filteredRatings, null, ['groups' => ['filter_rating']]);

        // Dans un premier temps, je vérifie que mon tableau associatif n'est pas vide, et je le stocke dans ma variable $reviews
        // Puis je fais une liste de traitement afin de vérifier que mes paramètres GET sont en place et j'utilise les méthodes du Review Repository
        // afin de gérer le tri des avis par date et par note
        // Si on ne rentre dans aucun de ces traitements, on appelle la méthode findAll du repository afin d'avoir accès à tous les avis
        if (!empty($filteredArray)) {
            $reviews = $filteredArray;
        }
        elseif (isset($_GET['date1'])) {
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

        // On envoit le tout à la vue twig
        return $this->render('home/index.html.twig', [
            'reviews' => $reviews,
            'filteredArray' => $filteredArray,
            'name' => $name ?? null,
        ]);
    }

    /**
     * @Route("/review", name="review")
     * 
     * Cette méthode me permet d'avoir accès au formulaire pour poster un avis sur la page d'accueil
     */
    public function review(Request $request, SluggerInterface $slugger)
    {
        // On instancie un nouvel objet Review
        $review = new Review();

        // On créé le formulaire qui est relié à l'entité Review
        $form = $this->createForm(ReviewType::class, $review);

        // On demande au formulaire de traité la requête
        $form->handleRequest($request);

        // On vérifie que le formulaire est bien soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les données du formulaire
            $review = $form->getData();

            // On met la date actuelle dans la création de l'avis
            $review->setCreatedAt(new \DateTime());

            // On récupère les images uploadées
            $uploadedImages = $form['imageReviews']->getData();

            // On vérifie qu'il y a bien des images uploadées
            if ($uploadedImages) {

                // On boucle sur les images uploadées car on a autorisé l'upload multiple dans le formulaire ReviewType
                foreach ($uploadedImages as $image) {

                    // On recupère les informations de chemin du fichier
                    $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
    
                    // On utilise le slugger de Symfony afin d'avoir un nom de fichier propre
                    $safeFilename = $slugger->slug($originalFilename);
    
                    // On génère un nouveau nom de fichier avec un id unique
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
    
                    // On copie le fichier dans le dossier uploads
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    
                    // On instancie un nouvel objet ImageReviews, qui est une entité spécialement créé pour l'upload des images
                    // On lui attribue le nom du fichier
                    // Puis on ajoute l'image
                    $img = new ImageReviews();
                    $img->setName($newFilename);
                    $review->addImageReview($img);
                }
            }

            // Message flash qui apparaîtra lors de la bonne soumission d'un avis via le formulaire
            $this->addFlash('success', 'Votre avis a bien été pris en compte.');

            // On enregistre toutes les données en base de données
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            // Après bonne soumission du formulaire, on fait une redirection sur la page d'accueil
            return $this->redirectToRoute('home');
        }

        // Permet d'afficher le formulaire dans la vue twig
        return $this->render('review/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
