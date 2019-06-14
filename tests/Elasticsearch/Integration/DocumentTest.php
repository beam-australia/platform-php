<?php

namespace Tests\Elasticsearch\Integration;

use Beam\Elasticsearch\Utilities;
use Tests\Fixtures\Person;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function documents_can_return_their_keys()
    {
        foreach (Utilities::getIndexables() as $class) {
            $indexable = new $class;
            $this->assertEquals($indexable->id, $indexable->getDocumentKey());
        }
    }

    /**
     * @test
     */
    public function documents_can_return_their_body()
    {
        foreach (Utilities::getIndexables() as $class) {
            $indexable = new $class;
            $this->assertTrue(is_array($indexable->getDocumentBody()));
        }
    }

    /**
     * @test
     */
    public function documents_can_return_their_type()
    {
        foreach (Utilities::getIndexables() as $class) {
            $indexable = new $class;
            $this->assertEquals($indexable->getTable(), $indexable->getDocumentType());
        }
    }

    /**
     * @test
     */
    public function documents_can_return_their_mappings()
    {
        foreach (Utilities::getIndexables() as $class) {
            foreach ((new $class)->getDocumentMappings() as $mapping) {
                $this->assertTrue(array_has($mapping, 'type'));
            }
        }

        $person = factory(Person::class)->create();

        $this->assertEquals($person->getDocumentMappings(), [
            'first_name' => ['type' => 'keyword'],
            'last_name' => ['type' => 'keyword'],
            'full_name' => ['type' => 'text'],
            'age' => ['type' => 'integer'],
            'sex' => ['type' => 'keyword'],
            'email' => ['type' => 'keyword'],
        ]);
    }

    /**
     * @test
     */
    public function documents_can_return_their_relations()
    {
        $person = factory(Person::class)->create();

        $this->assertEquals($person->getDocumentRelations(), ['family']);
    }

    /**
     * @test
     */
    public function it_can_determine_if_a_relation_is_indexable()
    {
        $person = factory(Person::class)->create();

        $this->assertTrue($person->isIndexableRelation('family'));

        $this->assertFalse($person->isIndexableRelation('foo'));

        $this->assertFalse($person->isIndexableRelation('posts'));
    }
}
