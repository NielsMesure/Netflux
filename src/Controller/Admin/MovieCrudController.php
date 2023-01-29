<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
          TextField::new('name','Nom'),
            SlugField::new('slug')
                ->setTargetFieldName('name'),
            ImageField::new('illustration','Affiche')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),

            TextField::new('subtitle','Sous-titre'),
            TextareaField::new('description'),
            BooleanField::new('isBest','Afficher en avant'),
            AssociationField::new('category','Catégories'),
            ImageField::new('illustrationHeaders','Bannière')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),


            ImageField::new('movieLink','film')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')



        ];
    }

}
