<?php

namespace Tests\Feature\Fields;

use App\Models\Field;
use Tests\TestCase;

class GetFieldTest extends TestCase {

    /** @test */
    public function it_see_fields_in_route_fields_index()
    {
        $field = create(Field::class);

        $this->adminLogin()
            ->getJson(
                route('fields.index')
            )
            ->assertSee($field->title);
    }


    /** @test */
    public function it_see_field_in_route_fields_index_in_this_format()
    {
        create(Field::class);
        $this->adminLogin()->getJson(route('fields.index'))
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id', 'title', 'icon'
                        ]
                    ]
                ]
            );
    }
}
