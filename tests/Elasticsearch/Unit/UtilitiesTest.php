<?php

namespace Tests\Elasticsearch\Unit;

use Beam\Elasticsearch\Utilities;
use Tests\Fixtures;
use Tests\TestCase;

class UtilitiesTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_translate_operators()
    {
        $this->assertEquals('gte', Utilities::translateOperator('>='));
        $this->assertEquals('lte', Utilities::translateOperator('<='));
        $this->assertEquals('gt', Utilities::translateOperator('>'));
        $this->assertEquals('lt', Utilities::translateOperator('<'));
    }

    /**
     * @test
     */
    public function it_returns_original_operator_when_unable_to_translate()
    {
        $this->assertEquals('like', Utilities::translateOperator('like'));
    }

    /**
     * @test
     */
    public function it_can_determine_if_a_model_is_soft_deletable()
    {
        $this->assertTrue(
            Utilities::isSoftDeletable(Fixtures\Person::class)
        );

        $this->assertFalse(
            Utilities::isSoftDeletable(Fixtures\Family::class)
        );
    }

    /**
     * @test
     */
    public function it_can_determine_if_a_model_is_indexable()
    {
        $this->assertTrue(
            Utilities::isIndexable(new Fixtures\Person)
        );
    }

    /**
     * @test
     */
    public function it_can_determine_if_a_model_is_not_indexable()
    {
        $this->assertFalse(
            Utilities::isIndexable(new Fixtures\Post)
        );
    }

    /**
     * @test
     */
    public function it_can_return_indexables()
    {
        $this->assertEquals(Utilities::getIndexables(), [
            Fixtures\Person::class,
            Fixtures\Family::class,
            Fixtures\Vehicle::class,
        ]);
    }

    /**
     * @test
     */
    public function it_can_return_config_values()
    {
        $this->assertEquals(Utilities::config('index'), 'test-index');

        $this->assertEquals(Utilities::config('ninjas.are.cool', ['default', 'foo']), ['default', 'foo']);

        $this->assertEquals(Utilities::config(), config('elasticsearch'));
    }

    /**
     * @test
     */
    public function it_plucks_errors_from_a_response()
    {
        // Always returns array
        $this->assertEquals(Utilities::getResponseErrors([]), []);

        // Returns errors
        $errors = Utilities::getResponseErrors([
            'errors' => true,
            'items' => ['FOO','BAR'],
        ]);

        $this->assertEquals($errors, ['FOO','BAR']);
    }
}
