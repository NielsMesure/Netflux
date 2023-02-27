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
                ->setBasePath('uploads/illustrations/')
                ->setUploadDir('public/uploads/illustrations')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setSortable(false)
                ->setFormTypeOption('required' ,false),

            TextField::new('subtitle','Date de Sortie'),
            TextareaField::new('description'),
            BooleanField::new('isBest','Afficher en avant'), 
            AssociationField::new('category','Catégories'),
            ImageField::new('illustrationHeaders','Bannière')
                ->setBasePath('uploads/banners/')
                ->setUploadDir('public/uploads/banners')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setSortable(false)
                ->setFormTypeOption('required' ,false),



            ImageField::new('movieLink','film')
                ->setBasePath('uploads/movies/')
                ->setUploadDir('public/uploads/movies')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setSortable(false)
                ->setFormTypeOption('required' ,false),

            ImageField::new('subtitles','Sous-Titres')
                ->setBasePath('uploads/subtitles/')
                ->setUploadDir('public/uploads/subtitles')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setSortable(false)
                ->setFormTypeOption('required' ,false),



        ];
    }

}
