<?php

it('route / has "Welcome" in it', function () {
    $page = visit('/');

    $page->assertSee('Welcome');
});