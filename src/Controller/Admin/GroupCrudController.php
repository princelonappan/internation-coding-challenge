<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class GroupCrudController extends AbstractCrudController
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public static function getEntityFqcn(): string
    {
        return Group::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $addUsers = Action::new('addUserGroup', 'Manage Users', 'fa fa-file-invoice')
            ->linkToRoute('user-group', function (Group $group): array {
                return [
                    'id' => $group->getId()
                ];
            });
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $addUsers)
            ->disable(Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->showEntityActionsInlined();
    }
}
