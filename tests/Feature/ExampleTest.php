<?php

it('redirects the homepage to the events list', function () {
    $response = $this->get('/');

    $response->assertRedirect('/events');
});
