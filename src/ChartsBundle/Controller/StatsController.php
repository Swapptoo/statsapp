<?php
/**
 * Stats Gathering Application
 *
 * @copyright  Copyright (C) 2014 WebSpark, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GNU General Public License Version 3
 */

namespace StatsApp\ChartsBundle\Controller;

use StatsApp\CoreBundle\Controller\BaseController;

/**
 * Class StatsController
 */
class StatsController extends BaseController
{

    /**
     * Displays the stats data for a specific application
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function appAction($application)
    {
        return $this->render('StatsAppChartsBundle:Stats:index.html.php', array('application' => $application));
    }

    /**
     * Receives the POSTed data from downstream applications
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendAction()
    {
        $data = array();

        // Fetch our data from the POST
        $postData = [
            'application' => $this->request->request->get('application', null),
            'version'     => $this->request->request->get('version', null),
            'phpVersion'  => $this->request->request->get('php_version', null),
            'dbDriver'    => $this->request->request->get('db_driver', null),
            'dbVersion'   => $this->request->request->get('db_version', null),
            'instanceId'  => $this->request->request->get('instance_id', null),
            'serverOs'    => $this->request->request->get('server_os', null)
        ];

        // Check for null values on the app, version, and instance; everything else we can do without
        if ($postData['application'] === null || $postData['version'] === null || $postData['instanceId'] === null) {
            $data['message'] = 'Missing data from the POST request';

            return $this->sendJsonResponse($data, 500);
        }

        /** @var \StatsApp\ChartsBundle\Model\StatsModel $model */
        $model = $this->factory->getModel('charts.stats');

        $entity = $model->getEntity($postData['instanceId']);

        // Loop over the post data and set it to the entity
        foreach ($postData as $key => $value) {
            $method = 'set' . ucwords($key);
            $entity->$method($value);
        }

        // Save the data
        try {
            $model->saveEntity($entity);

            $data['message'] = 'Data saved successfully';
            $code            = 200;
        } catch (\Exception $exception) {
            $data['message'] = 'An error occurred while saving the data';
            $code            = 500;
        }

        return $this->sendJsonResponse($data, $code);
    }

    /**
     * Displays the stats data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction()
    {
        return $this->render('StatsAppChartsBundle:Stats:index.html.php');
    }
}
