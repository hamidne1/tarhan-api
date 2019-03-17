<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

    use CreatesApplication;

    /**
     * custom authenticate wrap method for customer guard
     *
     * @param null $user
     * @return $this
     */
    protected function customerLogin($user = null)
    {
        $user = $user ?: create(User::class);

        $this->be($user, 'customer');

        return $this;
    }
}
