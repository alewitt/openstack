<?php

namespace OpenStack\Test\BlockStorage\v2\Models;

use GuzzleHttp\Psr7\Response;
use OpenStack\BlockStorage\v2\Api;
use OpenStack\BlockStorage\v2\Models\Snapshot;
use OpenStack\Test\TestCase;

class SnapshotTest extends TestCase
{
    /** @var Snapshot */
    private $snapshot;

    public function setUp()
    {
        parent::setUp();

        $this->rootFixturesDir = dirname(__DIR__);

        $this->snapshot = new Snapshot($this->client->reveal(), new Api());
        $this->snapshot->id = 1;
    }

    public function test_it_updates()
    {
        $this->snapshot->name = 'foo';
        $this->snapshot->description = 'bar';

        $expectedJson = ['snapshot' => ['name' => 'foo', 'description' => 'bar']];
        $this->setupMock('PUT', 'snapshots/1', $expectedJson, [], 'GET_snapshot');

        $this->snapshot->update();
    }

    public function test_it_deletes()
    {
        $this->setupMock('DELETE', 'snapshots/1', null, [], new Response(204));

        $this->snapshot->delete();
    }

    public function test_it_gets_metadata()
    {
        $this->setupMock('GET', 'snapshots/1/metadata', null, [], 'GET_metadata');

        $expected = [
            'foo' => '1',
            'bar' => '2',
        ];

        $this->assertEquals($expected, $this->snapshot->getMetadata());
    }
}