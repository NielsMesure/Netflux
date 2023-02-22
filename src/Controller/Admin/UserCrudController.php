<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Firstname','Prénom'),
            TextField::new('Lastname','Nom'),
            EmailField::new('email','E-mail'),
            TelephoneField::new('mobilePhone','Numéro de téléphone'),
            ChoiceField::new('Roles')
                ->allowMultipleChoices()

                ->setChoices([  'User' => 'ROLE_USER',
                        'Admin' => 'ROLE_ADMIN',
                        'Ban'=>'ROLE_BAN']
                ),

        ];
    }





}
