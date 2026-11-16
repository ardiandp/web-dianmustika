<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdminArticleCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::findOrCreate('manage-article-categories');
    }

    public function test_admin_can_edit_and_update_an_article_category(): void
    {
        $user = User::factory()->create()->givePermissionTo('manage-article-categories');
        $category = ArticleCategory::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.article-categories.edit', $category))
            ->assertOk()
            ->assertSee($category->name);

        $this->actingAs($user)
            ->put(route('admin.article-categories.update', $category), [
                'name' => 'Perawatan Baru',
                'description' => 'Deskripsi diperbarui',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.article-categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('article_categories', [
            'id' => $category->id,
            'name' => 'Perawatan Baru',
            'slug' => 'perawatan-baru',
            'description' => 'Deskripsi diperbarui',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_delete_an_article_category_without_deleting_its_articles(): void
    {
        $user = User::factory()->create()->givePermissionTo('manage-article-categories');
        $category = ArticleCategory::factory()->create();
        $article = Article::factory()->create(['article_category_id' => $category->id]);

        $this->actingAs($user)
            ->delete(route('admin.article-categories.destroy', $category))
            ->assertRedirect(route('admin.article-categories.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('article_categories', ['id' => $category->id]);
        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'article_category_id' => null,
        ]);
    }
}
