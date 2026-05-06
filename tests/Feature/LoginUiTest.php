<?php

namespace Tests\Feature;

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class LoginUiTest extends TestCase
{
    public function test_login_page_renders(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('WhatsApp AI Sales Agent')
            ->assertSee('Masuk ke dashboard');
    }

    public function test_login_page_contains_required_form_controls(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('name="email"', false)
            ->assertSee('type="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('type="password"', false)
            ->assertSee('data-error-area', false)
            ->assertSee('data-submit-button', false);
    }

    public function test_login_page_can_display_validation_errors(): void
    {
        $errors = (new ViewErrorBag())->put('default', new MessageBag([
            'email' => 'Email wajib diisi.',
        ]));

        $this->withSession(['errors' => $errors])
            ->get('/login')
            ->assertOk()
            ->assertSee('Email wajib diisi.');
    }
}
