<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesApplication;
use Tests\TestCase;

class PermissionUserTest extends TestCase
{
//    use CreatesApplication, RefreshDatabase, DatabaseMigrations;

    public function test_give_permission_to_an_user()
    {
        //arrange
        /* @var $user User*/
        $user = User::factory()->createOne();
        $permission = "edit-posts";

        //act
        $user->givePermission($permission);

        //assert
        $this->assertTrue($user->hasPermission($permission));
        $this->assertDatabaseHas('permissions', [
            'permission' => $permission
        ]);
    }
}
