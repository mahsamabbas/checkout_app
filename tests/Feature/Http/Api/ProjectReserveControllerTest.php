<?php

namespace Tests\Feature\Http\Api;

use Http;
use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use JMac\Testing\Traits\AdditionalAssertions;
use App\Http\Services\MailchimpMembersService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Requests\Api\ProjectReserveStoreRequest;
use App\Http\Controllers\Api\ProjectReserveController;

class ProjectReserveControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase;

    /**
     * @test
     */
    public function it_stores_and_redirect()
    {
        $this->withoutExceptionHandling();
        $this->actingAs(User::factory()->create());

        /** @var Project $project */
        $project = Project::factory()->create();

        Http::fake();

        $mailchimpMembersServiceMock = Mockery::mock(MailchimpMembersService::class)->makePartial();
        $mailchimpMembersServiceMock->shouldReceive('importMember')->andReturn(true);
        $this->app->bind(MailchimpMembersService::class, function () use ($mailchimpMembersServiceMock) {
            return $mailchimpMembersServiceMock;
        });

        $email = "testing@email.test";
        $response = $this->post(route("api.projects.reserve", $project), [
            "email" => $email
        ]);

        $response->assertRedirect($project->lead_redirect_url . "?email" . $email);
    }

    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            ProjectReserveController::class,
            'store',
            ProjectReserveStoreRequest::class
        );
    }
}