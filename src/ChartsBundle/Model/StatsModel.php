<?php
/**
 * Stats Gathering Application
 *
 * @copyright  Copyright (C) 2014 WebSpark, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License Version 3
 */

namespace StatsApp\ChartsBundle\Model;

use StatsApp\ChartsBundle\Entity\Stats;
use StatsApp\CoreBundle\Model\BaseModel;

/**
 * Class StatsModel
 */
class StatsModel extends BaseModel
{

    /**
     * {@inheritdoc}
     *
     * @return Stats|null
     */
    public function getEntity($id = null)
    {
        if ($id === null) {
            return new Stats();
        }

        return parent::getEntity($id);
    }

    /**
     * {@inheritdoc}
     *
     * @return \StatsApp\ChartsBundle\Entity\StatsRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('StatsAppChartsBundle:Stats');
    }
}
