<?php

namespace Tests\Feature\Fields;

use App\Models\Field;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteFieldTest extends TestCase
{
    /**
     * send the request to destroy the Field
     *
     * @param null $fieldId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($fieldId = null)
    {
        $fieldId = $fieldId ?: create(Field::class)->id;

        return $this->deleteJson(
            route('fields.destroy', $fieldId)
        );
    }


    /** @test */
    public function guest_can_not_delete_a_field()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_a_field()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }


    /** @test */
    public function an_authenticated_admin_can_delete_a_field()
    {
        $field = create(Field::class);

        $this->adminLogin()
            ->destroy($field->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('fields', $field->toArray());
    }
}
