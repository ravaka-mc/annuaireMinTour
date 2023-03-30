<?php

namespace App\Repository;

use App\Entity\Etablissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etablissement>
 *
 * @method Etablissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etablissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etablissement[]    findAll()
 * @method Etablissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtablissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etablissement::class);
    }

    public function add(Etablissement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Etablissement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Etablissement[] Returns an array of Etablissement objects
     */
    public function search($keyword = "", $region = "", $activite =""): array
    {
        $query = $this->createQueryBuilder('e')
        ->andWhere('e.statut = :statut')
        ->setParameter('statut', 'valide')
        ->andWhere('e.nom LIKE :keyword')
        ->setParameter('keyword', '%' . $keyword . '%')
        ->orderBy('e.created_at', 'DESC');
        //->setMaxResults(10)

        if($region != ""){
            $query = $query->andWhere('e.region = :region')
            ->setParameter('region', $region);
        } 

        if($activite != ""){
            $query = $query->join('e.activites', 'activite')
            ->andWhere('activite.id = :activite')
            ->setParameter('activite', $activite);
        }

        return $query->getQuery()->getResult();
    }
}
