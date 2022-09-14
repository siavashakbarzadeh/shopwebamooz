<?php

namespace Jokoli\Ticket\Feature;

use App\Models\TicketReply;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Ticket\Enums\TicketStatus;
use Jokoli\Ticket\Models\Ticket;
use Jokoli\Ticket\Repository\TicketRepository;
use Tests\TestCase;

class TicketTest extends TestCase
{
    public function test_authenticated_user_can_see_tickets()
    {
        $this->actingAsUser()->get(route('tickets.index'))->assertOk();
    }

    public function test_guest_user_can_not_see_tickets()
    {
        $this->get(route('tickets.index'))
            ->assertStatus(302)
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_see_her_tickets()
    {
        $this->actingAsUser();
        Ticket::factory()->count(5)->create();
        $this->get(route('tickets.index'))->assertOk()
            ->assertViewHas('tickets', resolve(TicketRepository::class)->paginate(request()));
    }

    public function test_permitted_user_can_see_all_tickets()
    {
        $this->actingAsUser();
        Ticket::factory()->count(2)->create();
        $this->actingAsUser();
        Ticket::factory()->count(1)->create();
        $this->actingAsUser()->get(route('tickets.index'))
            ->assertViewHas('tickets', resolve(TicketRepository::class)->paginate(request()));
    }

    public function test_authenticated_user_can_create_ticket()
    {
        $this->actingAsUser()->get(route('tickets.create'))->assertOk();
    }

    public function test_guest_user_can_not_create_ticket()
    {
        $this->get(route('tickets.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_store_ticket()
    {
        Storage::fake('private');
        $this->actingAsUser()
            ->assertDatabaseCount(Ticket::class, 0)
            ->assertDatabaseCount(TicketReply::class, 0)
            ->post(route('tickets.store'), [
                'title' => $this->faker->sentence,
                'body' => $this->faker->text,
            ])->assertStatus(302)
            ->assertRedirect(route('tickets.index'));
        $this->assertDatabaseCount(Ticket::class, 1)
            ->assertDatabaseCount(TicketReply::class, 1);
    }

    public function test_guest_user_can_not_store_ticket()
    {
        $this->assertDatabaseCount(Ticket::class, 0)
            ->assertDatabaseCount(TicketReply::class, 0)
            ->post(route('tickets.store'), [
                'title' => $this->faker->sentence,
                'body' => $this->faker->text,
            ])->assertRedirect(route('login'));
        $this->assertDatabaseCount(Ticket::class, 0)
            ->assertDatabaseCount(TicketReply::class, 0);
    }

    public function test_permitted_user_or_owner_can_see_show_ticket()
    {
        $this->actingAsUser();
        Ticket::factory()->has(TicketReply::factory(), 'replies')->create();
        $this->get(route('tickets.show', 1))->assertOk();
        $this->actingAsUserWithPermission(Permissions::ManageTickets)
            ->get(route('tickets.show', 1))
            ->assertOk();
    }

    public function test_normal_user_or_owner_can_not_see_show_ticket()
    {
        $this->actingAsUser();
        Ticket::factory()->has(TicketReply::factory(), 'replies')->create();
        $this->actingAsUser()->get(route('tickets.show', 1))->assertForbidden();
    }

    public function test_permitted_user_or_owner_can_close_ticket()
    {
        $this->actingAsUser();
        Ticket::factory(2)->has(TicketReply::factory(), 'replies')->create();

        $this->assertDatabaseMissing(Ticket::class, ['id' => 1, 'status' => TicketStatus::Close])
            ->patch(route('tickets.close', 1))->assertOk();
        $this->assertDatabaseHas(Ticket::class, ['id' => 1, 'status' => TicketStatus::Close]);

        $this->actingAsUserWithPermission(Permissions::ManageTickets)
            ->assertDatabaseMissing(Ticket::class, ['id' => 2, 'status' => TicketStatus::Close])
            ->patch(route('tickets.close', 2))->assertOk();
        $this->assertDatabaseHas(Ticket::class, ['id' => 2, 'status' => TicketStatus::Close]);
    }

    public function test_normal_user_can_not_close_ticket()
    {
        $this->actingAsUser();
        Ticket::factory()->has(TicketReply::factory(), 'replies')->create();
        $this->actingAsUser()
            ->assertDatabaseMissing(Ticket::class, ['id' => 1, 'status' => TicketStatus::Close])
            ->patch(route('tickets.close', 1))->assertForbidden();
        $this->assertDatabaseMissing(Ticket::class, ['id' => 1, 'status' => TicketStatus::Close]);
    }

    public function test_permitted_user_can_destroy_ticket()
    {
        $this->actingAsUser();
        Ticket::factory()->has(TicketReply::factory(), 'replies')->create();
        $this->actingAsUserWithPermission(Permissions::ManageTickets)
            ->assertDatabaseCount(Ticket::class, 1)
            ->delete(route('tickets.destroy', 1))
            ->assertOk();
        $this->assertDatabaseCount(Ticket::class, 0);
    }

    public function test_normal_user_can_not_destroy_ticket()
    {
        $this->actingAsUser();
        Ticket::factory()->has(TicketReply::factory(), 'replies')->create();
        $this->assertDatabaseCount(Ticket::class, 1)
            ->delete(route('tickets.destroy', 1))
            ->assertForbidden();
        $this->assertDatabaseCount(Ticket::class, 1);
    }

    public function test_permitted_user_or_owner_can_store_reply_for_ticket()
    {
        $this->actingAsUser();
        Ticket::factory()->has(TicketReply::factory(), 'replies')->create();

        $this->assertDatabaseCount(TicketReply::class, 1)
            ->post(route('tickets.reply', 1), [
                'body' => $this->faker->text,
            ])->assertRedirect(route('tickets.show', 1));
        $this->assertDatabaseCount(TicketReply::class, 2);

        $this->actingAsUserWithPermission(Permissions::ManageTickets)
            ->assertDatabaseCount(TicketReply::class, 2)
            ->post(route('tickets.reply', 1), [
                'body' => $this->faker->text,
            ])->assertRedirect(route('tickets.show', 1));
        $this->assertDatabaseCount(TicketReply::class, 3);
    }

    public function test_normal_user_can_not_store_reply_for_ticket()
    {
        $this->actingAsUser();
        Ticket::factory()->has(TicketReply::factory(), 'replies')->create();

        $this->actingAsUser()
            ->assertDatabaseCount(TicketReply::class, 1)
            ->post(route('tickets.reply', 1), [
                'body' => $this->faker->text,
            ])->assertForbidden();
        $this->assertDatabaseCount(TicketReply::class, 1);
    }
}
