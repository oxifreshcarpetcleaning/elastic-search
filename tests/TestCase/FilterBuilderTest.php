<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.0.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\ElasticSearch\Test;

use Cake\ElasticSearch\Datasource\Connection;
use Cake\ElasticSearch\FilterBuilder;
use Cake\ElasticSearch\Query;
use Cake\ElasticSearch\Type;
use Cake\TestSuite\TestCase;
use Elastica\Filter;

/**
 * Tests the FilterBuilder class
 *
 */
class FilterBuilderTest extends TestCase
{

    /**
     * Tests the between() filter
     *
     * @return void
     */
    public function testBetween()
    {
        $builder = new FilterBuilder;
        $result = $builder->between('price', 10, 100);
        $expected = [
            'range' => ['price' => ['gte' => 10, 'lte' => 100]]
        ];
        $this->assertEquals($expected, $result->toArray());

        $result = $builder->between('price', '2014', '2015');
        $expected = [
            'range' => ['price' => ['gte' => '2014', 'lte' => '2015']]
        ];
        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * Tests the bool() filter
     *
     * @return void
     */
    public function testBool()
    {
        $builder = new FilterBuilder;
        $result = $builder->bool();
        $this->assertInstanceOf('Elastica\Filter\Bool', $result);
    }

    /**
     * Tests the exists() filter
     *
     * @return void
     */
    public function testExists()
    {
        $builder = new FilterBuilder;
        $result = $builder->exists('comments');
        $expected = [
            'exists' => ['field' => 'comments']
        ];
        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * Tests the geoBoundingBox() filter
     *
     * @return void
     */
    public function testGeoBoundingBox()
    {
        $builder = new FilterBuilder;
        $result = $builder->geoBoundingBox('location', [40.73, -74.1], [40.01, -71.12]);
        $expected = [
            'geo_bounding_box' => [
                'location' => [
                    'top_left' => [40.73, -74.1],
                    'bottom_right' => [40.01, -71.12]
                ]
            ]
        ];
        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * Tests the geoDistance() filter
     *
     * @return void
     */
    public function testGeoDistance()
    {
        $builder = new FilterBuilder;
        $result = $builder->geoDistance('location', ['lat' => 40.73, 'lon' => -74.1], '10km');
        $expected = [
            'geo_distance' => [
                'location' => ['lat' => 40.73, 'lon' => -74.1],
                'distance' => '10km'
            ]
        ];
        $this->assertEquals($expected, $result->toArray());

        $result = $builder->geoDistance('location', 'dr5r9ydj2y73', '10km');
        $expected = [
            'geo_distance' => [
                'location' => 'dr5r9ydj2y73',
                'distance' => '10km'
            ]
        ];
        $this->assertEquals($expected, $result->toArray());
    }
}