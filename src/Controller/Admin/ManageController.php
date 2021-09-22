<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\ColectionRepository;
use App\Repository\ProductRepository;
use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageController extends AbstractController
{

    private $arrayOfPictureFiles; //tableau contenant tous les noms des fichiers image

    private $directory; //chemin de base du projet

    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;

        $this->arrayOfPictureFiles= [];
        
        $this->directory = $this->parameterBag->get('kernel.project_dir').'/public/pictures'; // récupère le chemin des images

        // peut aussi récupérer le chemin comme ça, si directement dans une methode du controller:
        // $this->getParameter('kernel.project_dir').'/public/pictures'; 

        $folder=opendir($this->directory); // ouvre le dossier des images

        while($fileName = readdir($folder)){

            if($fileName != '.' && $fileName != '..'){ //ne tiens pas compte des fichiers cachés

                $this->arrayOfPictureFiles[]=$fileName; //met tout les noms de fichier dans le tableau:
            }                                           //arrayOfPictureFiles
        }
        closedir($folder); 
    }


    /**
     * @Route("/admin/manage/pictures", name="admin_manage_pictures")
     */
    public function managePictures(ProductRepository $productRepo, CategoryRepository $categoryRepo, ColectionRepository $colectionRepo, SliderRepository $sliderRepo): Response
    {
        // $arr=$productRepo->getNameAndCategoryOfAllProducts();
        // dd($arr);
        function hasResult($string, $search) {
            return strpos($string, $search) !== false;
        }

        $arrayOfPictures = []; //tableau de toutes les images (même index que $arrayOfPictureFiles) avec en plus les infos sur l'utilisation de l'image.
        $arrayOfProducts = $productRepo->findAll();
        $arrayOfCategories = $categoryRepo->findAll();
        $arrayOfColections = $colectionRepo->findAll();
        $arrayOfSliders = $sliderRepo->findAll();
        $arrayOfProductsPicture1 = [];
        $arrayOfProductsPicture2 = [];
        $arrayOfProductsPicture3 = [];
        $arrayOfCategoryPicture = [];
        $arrayOfColectionPicture = [];
        $arrayOfSliderPicture = [];

        foreach($this->arrayOfPictureFiles as $fileName){
                $arrayOfPictures[] = ["file_name"=> $fileName];
        }

        foreach($arrayOfProducts as $product){
            $arrayOfProductsPicture1[] = $product->getPicture1();
        }

        foreach($arrayOfProducts as $product){
            $arrayOfProductsPicture2[] = $product->getPicture2();
        }

        foreach($arrayOfProducts as $product){
            $arrayOfProductsPicture3[] = $product->getPicture3();
        }

        foreach($arrayOfCategories as $category){
            $arrayOfCategoryPicture[] = $category->getPicture();
        }

        foreach($arrayOfColections as $colection){
            $arrayOfColectionPicture[] = $colection->getPicture();
        }

        foreach($arrayOfSliders as $slider){
            $arrayOfSliderPicture[] = $slider->getPicture();
        }




        foreach($arrayOfPictures as $key=>$fileName){
            if (in_array($fileName['file_name'], $arrayOfProductsPicture1)) {
                $arrayOfPictures[$key]["productUse"]["picture1"] = true;
            }else{
                $arrayOfPictures[$key]["productUse"]["picture1"] = false;
            }

            if (in_array($fileName['file_name'], $arrayOfProductsPicture2)) {
                $arrayOfPictures[$key]["productUse"]["picture2"] = true;
            }else{
                $arrayOfPictures[$key]["productUse"]["picture2"] = false;
            }

            if (in_array($fileName['file_name'], $arrayOfProductsPicture3)) {
                $arrayOfPictures[$key]["productUse"]["picture3"] = true;
            }else{
                $arrayOfPictures[$key]["productUse"]["picture3"] = false;
            }

            if (in_array($fileName['file_name'], $arrayOfCategoryPicture)) {
                $arrayOfPictures[$key]["categoryUse"]["picture"] = true;
            }else{
                $arrayOfPictures[$key]["categoryUse"]["picture"] = false;
            }

            if (in_array($fileName['file_name'], $arrayOfColectionPicture)) {
                $arrayOfPictures[$key]["colectionUse"]["picture"] = true;
            }else{
                $arrayOfPictures[$key]["colectionUse"]["picture"] = false;
            }

            if (in_array($fileName['file_name'], $arrayOfSliderPicture)) {
                $arrayOfPictures[$key]["sliderUse"]["picture"] = true;
            }else{
                $arrayOfPictures[$key]["sliderUse"]["picture"] = false;
            }
        }

        return $this->render('admin/manage/manage_pictures.html.twig', 
        ['baseUrl_picture'=>'pictures/','array_of_pictures'=>$arrayOfPictures]);
    }

    /**
     * @Route("/admin/manage/pictures/delete/{id}", name="admin_manage_pictures_delete", methods={"DELETE"})
     */
    public function deletePictures($id, Request $request): Response
    {
        $csrfTokenFromForm = $request->request->get('_token'); //récupération du csrfToken du formulaire

        $tokenKey = 'delete-toto-image'.$id; //clé permettant de générer et vérifier le token

        if($this->isCsrfTokenValid($tokenKey, $csrfTokenFromForm)){

            unlink($this->directory.'/'.$this->arrayOfPictureFiles[$id]); //suppression du fichier
        }

        return $this->redirectToRoute('admin_manage_pictures');
    }
}

 