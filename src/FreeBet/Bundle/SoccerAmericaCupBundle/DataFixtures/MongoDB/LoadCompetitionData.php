<?php

namespace FreeBet\Bundle\SoccerAmericaCupBundle\DataFixtures\MongoDB;

use FreeBet\Bundle\CompetitionBundle\DataFixtures\AbstractCompetitionLoader;

/**
 * Description of LoadCompetitionData
 *
 * @author jobou
 */
class LoadCompetitionData extends AbstractCompetitionLoader
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        return array(
            array(
                'name' => 'Copa América 2015',
                'type' => 'soccer-america-cup',
                'subType' => null,
                'reference' => 'america-cup-2015',
                'endDate' => "2015-07-04"
            )
        );
    }
}
