<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;

class OrderCrudController extends AbstractCrudController
{

   private $entityManager;
   private $crudUrlGenerator;

   public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator )
   { 
    $this->entityManager=$entityManager;
    $this->crudUrlGenerator=$crudUrlGenerator;
   } 

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation','préparation en cours')->linkToCrudAction('updatePreparation');
        return $actions
        ->add('detail',$updatePreparation)
        ->add('index', 'detail');
    }

    public function updatePreparation(AdminContext $context)
    {
      $order= $context->getEntity()->getInstance();
      $order->setState(2);
      $this->entityManager->flush();
      $url = $this->crudUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();
            
      return $this->redirect($url);      
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id'=>'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passé le:'),
            TextField::new('user.getFullName','Client'),
            MoneyField::new('total','total produit')->setCurrency('EUR'),
            TextField::new('carrierName','Transporteur de client '),
            MoneyField::new('carrierPrice', 'frais de port')->setCurrency('EUR'),
            ChoiceField::new('state')->setChoices([
             'non payée'=>0,
             'Payée'=>1,
             'Préparation en cours'=>2,
             'livraison en cours'=>3
            ]),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex()

        ];
    }
    
}
