<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\GoogleAnalytics;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class PageViewsService
{
    /**
     * @var \Google_Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $profileId;

    /**
     * GetPageViews constructor.
     *
     * @param \Google_Client $client
     * @param string $profileId
     */
    public function __construct(\Google_Client $client, $profileId)
    {
        $this->client = $client;
        $this->profileId = $profileId;
    }

    /**
     * @param $pagePath
     * @return mixed
     */
    public function get($pagePath)
    {
        $analytics = new \Google_Service_Analytics($this->client);
        $results = $this->getResults($analytics, $this->profileId, $pagePath);

        if ($results->totalResults === 0) {
            return null;
        }

        return (int)$results->rows[0][1];
    }

    /**
     * @param \Google_Service_Analytics $analytics
     * @param $profileId
     * @param $pagePath
     *
     * @return \Google_Service_Analytics_GaData
     */
    protected function getResults(\Google_Service_Analytics $analytics, $profileId, $pagePath)
    {
        // Calls the Core Reporting API and queries for the number of sessions
        // for the last seven days.
        return $analytics->data_ga->get(
            'ga:' . $profileId,
            '2005-01-01',
            'today',
            'ga:pageViews',
            [
                'dimensions' => 'ga:pagePath',
                'filters' => 'ga:pagePath==' . $pagePath,
            ]);
    }
}
