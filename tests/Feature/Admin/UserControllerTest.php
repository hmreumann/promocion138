<?php

use App\Models\User;

beforeEach(function () {
    // Create an admin user for testing
    $this->admin = User::factory()->create([
        'email' => 'admin@test.com',
    ]);

    // Mock the admin config to include our test admin
    config(['admins.emails' => ['admin@test.com']]);
});

it('admin can view users index page', function () {
    $this->actingAs($this->admin);

    $response = $this->get(route('admin.users.index'));

    $response->assertStatus(200);
    $response->assertSee('Admin - User Management');
});

it('non-admin cannot access users index page', function () {
    $user = User::factory()->create(['email' => 'user@test.com']);
    $this->actingAs($user);

    $response = $this->get(route('admin.users.index'));

    $response->assertStatus(403);
});

it('admin can create a new user', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('admin.users.store'), [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'active' => '1',
        'payment_method' => 'smsv',
        'plan' => 'full',
        'cents' => 100,
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('success', 'User created successfully.');

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'active' => true,
        'payment_method' => 'smsv',
        'plan' => 'full',
        'cents' => 100,
    ]);
});

it('admin can create an inactive user', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('admin.users.store'), [
        'name' => 'Inactive User',
        'email' => 'inactive@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'active' => '0',
        'payment_method' => 'transfer',
        'plan' => 'basic',
        'cents' => 50,
    ]);

    $response->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseHas('users', [
        'email' => 'inactive@example.com',
        'active' => false,
    ]);
});

it('admin can update user from active to inactive', function () {
    $this->actingAs($this->admin);

    $user = User::factory()->create([
        'name' => 'Active User',
        'email' => 'active@example.com',
        'active' => true,
    ]);

    $response = $this->put(route('admin.users.update', $user), [
        'name' => 'Active User',
        'email' => 'active@example.com',
        'active' => '0', // Changing to inactive
        'payment_method' => 'smsv',
        'plan' => 'full',
        'cents' => 100,
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('success', 'User updated successfully.');

    $user->refresh();
    expect($user->active)->toBeFalse();
});

it('admin can update user from inactive to active', function () {
    $this->actingAs($this->admin);

    $user = User::factory()->create([
        'name' => 'Inactive User',
        'email' => 'inactive@example.com',
        'active' => false,
    ]);

    $response = $this->put(route('admin.users.update', $user), [
        'name' => 'Inactive User',
        'email' => 'inactive@example.com',
        'active' => '1', // Changing to active
        'payment_method' => 'transfer',
        'plan' => 'basic',
        'cents' => 200,
    ]);

    $response->assertRedirect(route('admin.users.index'));

    $user->refresh();
    expect($user->active)->toBeTrue();
});

it('admin can update user without changing password', function () {
    $this->actingAs($this->admin);

    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
        'password' => 'originalpassword',
    ]);

    $originalPassword = $user->password;

    $response = $this->put(route('admin.users.update', $user), [
        'name' => 'Updated Name',
        'email' => 'original@example.com',
        'active' => '1',
        'payment_method' => 'smsv',
        'plan' => 'full',
        'cents' => 150,
    ]);

    $response->assertRedirect(route('admin.users.index'));

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->password)->toBe($originalPassword); // Password should remain unchanged
});

it('admin can update user with new password', function () {
    $this->actingAs($this->admin);

    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => 'oldpassword',
    ]);

    $originalPassword = $user->password;

    $response = $this->put(route('admin.users.update', $user), [
        'name' => $user->name,
        'email' => 'user@example.com',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
        'active' => '1',
        'payment_method' => 'smsv',
        'plan' => 'full',
        'cents' => 100,
    ]);

    $response->assertRedirect(route('admin.users.index'));

    $user->refresh();
    expect($user->password)->not->toBe($originalPassword); // Password should be changed
});

it('admin can delete a user', function () {
    $this->actingAs($this->admin);

    $user = User::factory()->create([
        'email' => 'delete@example.com',
    ]);

    $response = $this->delete(route('admin.users.destroy', $user));

    $response->assertRedirect(route('admin.users.index'));
    $response->assertSessionHas('success', 'User deleted successfully.');

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('admin can view create user form', function () {
    $this->actingAs($this->admin);

    $response = $this->get(route('admin.users.create'));

    $response->assertStatus(200);
    $response->assertSee('Create User');
});

it('admin can view edit user form', function () {
    $this->actingAs($this->admin);

    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $response = $this->get(route('admin.users.edit', $user));

    $response->assertStatus(200);
    $response->assertSee('Edit User');
    $response->assertSee('Test User');
    $response->assertSee('test@example.com');
});

it('validates required fields when creating user', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('admin.users.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
});

it('validates email uniqueness when creating user', function () {
    $this->actingAs($this->admin);

    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->post(route('admin.users.store'), [
        'name' => 'New User',
        'email' => 'existing@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'active' => '1',
    ]);

    $response->assertSessionHasErrors(['email']);
});

it('validates password confirmation when creating user', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('admin.users.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different',
        'active' => '1',
    ]);

    $response->assertSessionHasErrors(['password']);
});

it('can filter users by search term', function () {
    $this->actingAs($this->admin);

    $user1 = User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    $user2 = User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

    $response = $this->get(route('admin.users.index', ['search' => 'John']));

    $response->assertStatus(200);
    $response->assertSee('John Doe');
});

it('can filter users by active status', function () {
    $this->actingAs($this->admin);

    $activeUser = User::factory()->create(['active' => true, 'name' => 'Active User']);
    $inactiveUser = User::factory()->create(['active' => false, 'name' => 'Inactive User']);

    $response = $this->get(route('admin.users.index', ['active' => '1']));

    $response->assertStatus(200);
    $response->assertSee('Active User');
});

it('can filter users by payment method', function () {
    $this->actingAs($this->admin);

    $smsvUser = User::factory()->create(['payment_method' => 'smsv', 'name' => 'SMSV User']);
    $transferUser = User::factory()->create(['payment_method' => 'transfer', 'name' => 'Transfer User']);

    $response = $this->get(route('admin.users.index', ['payment_method' => 'smsv']));

    $response->assertStatus(200);
    $response->assertSee('SMSV User');
});
