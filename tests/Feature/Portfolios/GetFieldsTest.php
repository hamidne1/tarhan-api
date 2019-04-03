<?php

namespace Tests\Feature\Portfolios;

use App\Models\Category_field;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFieldsTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return GetFieldsTest
     */
    protected function setData($override = [])
    {

        $this->data = create(Category_field::class, $override)->category_id;

        return $this;
    }

    /**
     * send the request to store the catalog
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function getFields()
    {

        return $this->adminLogin()->getJson(
            route('portfolio.fields', $this->data)
        );
    }

    /** @test */
    public function it_can_get_required_field()
    {
        $this->withoutExceptionHandling();
        $this->setData()
            ->getFields()
            ->assertJsonStructure(
                [
                    [
                        'id', 'title', 'icon',

                        'pivot' => [

                            'category_id', 'field_id'

                        ]
                    ]
                ]
            );

    }
}
