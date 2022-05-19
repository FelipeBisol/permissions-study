<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Tests\CreatesApplication;
use Tests\TestCase;

class PermissionUserTest extends TestCase
{
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

    public function test_user_without_access_permission()
    {
        //arrange
        $user = User::factory()->createOne();
        Route::get('test-permission', function (){
            return response()->json()->setStatusCode(200);
        })->middleware('verify-permission:access-full');

        //act
        $this->actingAs($user);

        //assert
        $this->get('test-permission')->assertForbidden();
    }

    public function test_using_policies_with_permissions()
    {
        //arrange
        /** @var User $user*/
        $user = User::factory()->createOne();
        $user2 = User::factory()->createOne();

        //act
        $post = $user->posts()->save(Post::factory()->make());

        //assert
        $this->actingAs($user2)
            ->delete(route('posts.delete', $post))
            ->assertForbidden();
    }
}
