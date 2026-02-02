<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('pages.member-area.settings')
        ->assertStatus(200);
});
