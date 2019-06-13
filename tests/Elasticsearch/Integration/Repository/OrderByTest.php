<?php

namespace Tests\Elasticsearch\Integration\Repositories;

use Carbon\Carbon;
use Beam\Elasticsearch\Testing\ResetElasticsearchIndex;
use Tests\Fixtures;
use Tests\Fixtures\Repositories\Elasticsearch\PersonRepository;
use Beam\Elasticsearch\Testing\Indexer;
use Tests\TestCase;

class OrderByTest extends TestCase
{
    use ResetElasticsearchIndex;

    /**
     * @test
     */
    public function it_can_order_by_a_date_field()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'created_at' => Carbon::now(),
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Barak',
            'created_at' => Carbon::now()->subDays(1),
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'George',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Bill',
            'created_at' => Carbon::now()->subDays(3),
        ]);

        Indexer::all(Fixtures\Person::class);

        $presidents = resolve(PersonRepository::class)
            ->orderBy('created_at', 'DESC')
            ->find();

        $this->assertEquals($presidents->pluck('first_name')->toArray(), [
            'Donald',
            'Barak',
            'George',
            'Bill',
        ]);

        $presidents = resolve(PersonRepository::class)
            ->orderBy('created_at', 'ASC')
            ->find();

        $this->assertEquals($presidents->pluck('first_name')->toArray(), [
            'Bill',
            'George',
            'Barak',
            'Donald',
        ]);
    }

    /**
     * @test
     */
    public function it_can_order_by_a_numeric_field()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'Barak',
            'age' => 56,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Bill',
            'age' => 71,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'age' => 72,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'George',
            'age' => 73,
        ]);

        Indexer::all(Fixtures\Person::class);

        $presidents = resolve(PersonRepository::class)
            ->orderBy('age', 'ASC')
            ->find();

        $this->assertEquals($presidents->pluck('first_name')->toArray(), [
            'Barak',
            'Bill',
            'Donald',
            'George',
        ]);

        $presidents = resolve(PersonRepository::class)
            ->orderBy('age', 'DESC')
            ->find();

        $this->assertEquals($presidents->pluck('first_name')->toArray(), [
            'George',
            'Donald',
            'Bill',
            'Barak',
        ]);
    }

    /**
     * @test
     */
    public function it_orders_by_ascending_defaultly()
    {
        factory(Fixtures\Person::class)->create([
            'first_name' => 'Barak',
            'age' => 56,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Bill',
            'age' => 71,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'Donald',
            'age' => 72,
        ]);

        factory(Fixtures\Person::class)->create([
            'first_name' => 'George',
            'age' => 73,
        ]);

        Indexer::all(Fixtures\Person::class);

        $presidents = resolve(PersonRepository::class)
            ->orderBy('age')
            ->find();

        $this->assertEquals($presidents->pluck('first_name')->toArray(), [
            'Barak',
            'Bill',
            'Donald',
            'George',
        ]);
    }
}
