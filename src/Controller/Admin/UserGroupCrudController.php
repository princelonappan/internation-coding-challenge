<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;

class UserGroupCrudController extends AbstractCrudController
{
    private $doctrine;
    private $crudUrlGenerator;

    public function __construct(ManagerRegistry $doctrine, AdminUrlGenerator $crudUrlGenerator)
    {
        $this->doctrine = $doctrine;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return UserGroup::class;
    }

    /**
     * @Route(
     *     "/admin/user-group/{id}",
     *     name="user-group"
     * )
     */
    public function showList(Request $request): Response
    {
        $id = $request->query->all()['routeParams']['id'];
        $url = $this->crudUrlGenerator
            ->setRoute('add-user-group', array('groupId' => $id))
            ->addSignature(true)
            ->generateUrl();
        $updateUrl = $this->crudUrlGenerator
            ->setRoute('update-user')
            ->addSignature(true)
            ->generateUrl();
        $redirectionUrl = $this->crudUrlGenerator
            ->setRoute('user-group', array('id' => $id))
            ->addSignature(true)
            ->generateUrl();
        $userGroups = $this->doctrine->getRepository(UserGroup::class)->findBy(array('groupId' => $id));
        $group = $this->doctrine->getRepository(Group::class)->find(array('id' => $id));
        return $this->render('admin/group/index.html.twig', [
            'userGroups' => $userGroups,
            'groupId' => $id,
            'group' => $group,
            'url' => $url,
            'updateUrl' => $updateUrl,
            'redirectionUrl' => $redirectionUrl
        ]);
    }

    /**
     * @Route(
     *     "/admin/group/add-user",
     *     name="add-user-group"
     * )
     */

    public function addUser(Request $request): Response
    {
        $groupId = $request->query->all()['routeParams']['groupId'];
        $repository = $this->doctrine->getRepository(User::class);
        $repositoryUserGroup = $this->doctrine->getRepository(UserGroup::class);

        $queryBuilder = $repositoryUserGroup->createQueryBuilder('ug');
        $queryBuilderUserGroup = $queryBuilder;
        $queryBuilderUserGroup->select('IDENTITY(ug.userId)')
            ->where('ug.groupId = ?1');
        $queryBuilder = $repository->createQueryBuilder('user');
        $queryBuilder->select('user.name, user.id, user.email')
            ->where($queryBuilder->expr()->notIn('user.id', $queryBuilderUserGroup->getDQL())
            );
        $queryBuilder->setParameter(1, $groupId);
        $query = $queryBuilder->getQuery();
        $users = $query->getResult();
        $url = $this->crudUrlGenerator
            ->setRoute('update-user')
            ->addSignature(true)
            ->generateUrl();
        $redirectionUrl = $this->crudUrlGenerator
            ->setRoute('user-group', array('id' => $groupId))
            ->addSignature(true)
            ->generateUrl();
        return $this->render('admin/group/add.html.twig', ['users' => $users,
            'groupId' => $groupId, 'url' => $url, 'redirectionUrl' => $redirectionUrl]);
    }

    /**
     * @Route(
     *     "/admin/group/update-user",
     *     name="update-user"
     * )
     */

    public function updateUser(Request $request): Response
    {
        $action = $request->request->get('action');
        $groupId = $request->request->get('groupId');
        $userIds = $request->request->get('userIds');
        if ($action == 'add') {
            if (!empty($userIds)) {
                $group = $this->doctrine->getRepository(Group::class)->find(array('id' => $groupId));
                foreach ($userIds as $_userId) {
                    $user = $this->doctrine->getRepository(User::class)->find(array('id' => $_userId));
                    $userGroups = $this->doctrine->getRepository(UserGroup::class)->
                    findBy(array('groupId' => $groupId, 'userId' => $_userId));
                    if (empty($userGroups)) {
                        $entityManager = $this->doctrine->getManager();
                        $userGroup = new UserGroup();
                        $userGroup->setGroupId($group);
                        $userGroup->setUserId($user);
                        $entityManager->persist($userGroup);
                        $entityManager->flush();
                    }
                }
            }
        } else {
            if (!empty($userIds)) {
                $userGroups = $this->doctrine->getRepository(UserGroup::class)->
                findBy(array('groupId' => $groupId, 'userId' => $userIds));
                if (!empty($userGroups)) {
                    $em = $this->doctrine->getManager();
                    $this->deleteEntities($em, $userGroups);
                }
            }
        }
        $response = new Response();
        $response->setContent(json_encode([
            'response' => 'sucess',
        ]));
        return $response;
    }

    protected function deleteEntities($em, $entities)
    {
        foreach ($entities as $entity) {
            $em->remove($entity);
        }
        $em->flush();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Manage User', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Manage Group', 'fa fa-users', Group::class);
    }
}
