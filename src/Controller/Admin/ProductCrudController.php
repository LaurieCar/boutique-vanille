<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('nom');
        yield TextField::new('slug')->setHelp("Identifiant unique utilisé dans l'URL, ex: vanille-de-madagascar");
        yield AssociationField::new('categorie');
        yield MoneyField::new('prix')->setCurrency('EUR')->setStoredAsCents();
        yield IntegerField::new('stock');
        yield BooleanField::new('actif');
        yield TextareaField::new('description')->hideOnIndex();

        yield ImageField::new('imagePrincipale')
            ->setBasePath('uploads/products/images')
            ->onlyOnIndex();
        yield Field::new('imageFile')
            ->setFormType(VichImageType::class)
            ->onlyOnForms()
            ->setLabel('Image');

        yield Field::new('videoFile')
            ->setFormType(VichFileType::class)
            ->onlyOnForms()
            ->setLabel('Vidéo (mp4/webm)');
    }
}
