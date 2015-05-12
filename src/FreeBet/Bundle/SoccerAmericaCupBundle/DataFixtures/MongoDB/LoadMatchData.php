<?php

namespace FreeBet\Bundle\SoccerAmericaCupBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FreeBet\Bundle\SoccerBundle\Document\Match;
use FreeBet\Bundle\CompetitionBundle\DataFixtures\AbstractDataLoader;

/**
 * Description of LoadMatchData
 *
 * @author jobou
 */
class LoadMatchData extends AbstractDataLoader implements
    OrderedFixtureInterface,
    ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function buildObject(array $data)
    {
        return $this->createMatch($data, 'america-cup-2015');
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $file = $this->container->getParameter('kernel.root_dir') .
            '/../src/FreeBet/Bundle/SoccerAmericaCupBundle/Resources/data/match.csv';

        return $this->container
            ->get('free_bet.data_loader.csv_file')
            ->readFile($file);
    }

    /**
     * Create a Match from data
     *
     * @param array $data
     * @param string $competitionReference
     *
     * @return \FreeBet\Bundle\SoccerBundle\Document\Match
     */
    protected function createMatch(array $data, $competitionReference)
    {
        $americaCup2014 = $this->getReference($competitionReference);

        $entity = new Match();
        $entity->setPhaseOrder($data[0]);
        $entity->setPhase($data[1]);
        $entity->setGroup($data[2]);
        if (!empty($data[3])) {
            $entity->setLeftName($data[3]);
        }
        if (!empty($data[4])) {
            $entity->setRightName($data[4]);
        }
        $entity->setDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data[5]));

        if (count($data) > 6) {
            $this->addScore($entity, $data);
        }

        $entity->setCompetition($americaCup2014);
        $entity->setProcessed(false);

        return $entity;
    }

    /**
     * Add match score data
     *
     * @param \FreeBet\Bundle\SoccerBundle\Document\Match $match
     * @param array $data
     *
     * @return void
     */
    protected function addScore(Match $match, $data)
    {
        $scoreResult = array(
            6 => "setLeftTeamHalfTimeScore",
            7 => "setRightTeamHalfTimeScore",
            8 => "setLeftTeamScore",
            9 => "setRightTeamScore",
            10 => "setLeftTeamAfterExtendedTimeScore",
            11 => "setRightTeamAfterExtendedTimeScore",
            12 => "setLeftTeamPenaltyScore",
            13 => "setRightTeamPenaltyScore"
        );

        foreach ($scoreResult as $key => $method) {
            if (isset($data[$key]) && (!empty($data[$key]) || $data[$key] === "0")) {
                $match->$method($data[$key]);
            }
        }
    }
}
