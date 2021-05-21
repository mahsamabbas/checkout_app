<?php

namespace Tests\Feature\Http\Controllers;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use App\Models\Project;
use App\Http\Requests\ProjectStoreRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use App\Http\Services\MailchimpMembersService;
use Illuminate\Foundation\Testing\RefreshDatabase;


/**
 * @see \App\Http\Controllers\ProjectController
 */
class ProjectControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $this->actingAs(User::factory()->create());
        Project::factory()->count(3)->create();
        $response = $this->get(route('projects.index'));
        $response->assertOk();
        $response->assertSeeLivewire("projects.index");
    }

    /**
     * @test
     */
    public function index_livewire_view_can_delete_project()
    {
        $this->actingAs(User::factory()->create());
        Project::factory()->count(3)->create();
        $response = $this->get(route('projects.index'));
        $response->assertOk();
        $response->assertSeeLivewire("projects.index");
        $project = Project::query()->first();

        Livewire::test("projects.index")
            ->call("delete", $project->id)
            ->assertDontSee($project->mailchimp_api_key);

        $this->assertDeleted($project);
    }

    /**
     * @test
     */
    public function create_displays_view()
    {
        $this->actingAs(User::factory()->create());
        $response = $this->get(route('projects.create'));
        $response->assertOk();
        $response->assertViewIs('project.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            ProjectController::class,
            'store',
            ProjectStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $this->actingAs(User::factory()->create());

        $this->mockMailchimpService();

        $response = $this->post(route('projects.store'), $factoryProject = $this->getProjectFactoryData());
        $response->assertSessionHasNoErrors();
        Project::query()
            ->where('mailchimp_api_key', $factoryProject['mailchimp_api_key'])
            ->get();

        $response->assertRedirect(route('projects.index'));
        $response->assertSessionHas('success');
    }

    /**
     *  Mock the MailchimpService class to pass the validation rules
     *
     */
    private function mockMailchimpService()
    {
        $mailchimpMembersServiceMock = Mockery::mock(MailchimpMembersService::class)->makePartial();

        $mailchimpMembersServiceMock->shouldReceive('checkIfKeyAndServerCorrect')->andReturn(true);
        $mailchimpMembersServiceMock->shouldReceive('checkIfListIdIsCorrect')->andReturn(true);

        $this->app->bind(MailchimpMembersService::class, function () use ($mailchimpMembersServiceMock) {
            return $mailchimpMembersServiceMock;
        });

    }

    /**
     * Get a factory project array of data
     *
     * @return array
     */
    private function getProjectFactoryData()
    {
        $factoryProject = Project::factory()->make();

        return [
            'name' => $factoryProject->name,
            'url' => $factoryProject->url,
            'reservation_cost' => $factoryProject->reservation_cost,
            'mailchimp_server' => $factoryProject->mailchimp_server,
            'mailchimp_api_key' => $factoryProject->mailchimp_api_key,
            'mailchimp_list_id' => $factoryProject->mailchimp_list_id,
            'lead_redirect_url' => $factoryProject->lead_redirect_url,
            'reservation_redirect_url' => $factoryProject->reservation_redirect_url,
            'data_app_project_id' => $factoryProject->data_app_project_id,
        ];
    }

    /**
     * @test
     */
    public function edit_displays_view()
    {
        $this->actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $response = $this->get(route('projects.edit', $project));

        $response->assertOk();
        $response->assertViewIs('project.edit');
        $response->assertViewHas('project');
    }

    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            ProjectController::class,
            'update',
            ProjectUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_redirects()
    {
        $this->actingAs(User::factory()->create());

        $this->mockMailchimpService();

        /** @var Project $project */
        $project = Project::factory()->create();


        $response = $this->put(route('projects.update', $project), $factoryProject = $this->getProjectFactoryData());
        $response->assertSessionHasNoErrors();

        $project->refresh();

        $response->assertRedirect(route('projects.index'));
        $response->assertSessionHas('success');

        $this->assertEquals($factoryProject['name'], $project->name);
        $this->assertEquals($factoryProject['url'], $project->url);
        $this->assertEquals($factoryProject['reservation_cost'], $project->reservation_cost);
        $this->assertEquals($factoryProject['mailchimp_server'], $project->mailchimp_server);
        $this->assertEquals($factoryProject['mailchimp_api_key'], $project->mailchimp_api_key);
        $this->assertEquals($factoryProject['mailchimp_list_id'], $project->mailchimp_list_id);
        $this->assertEquals($factoryProject['lead_redirect_url'], $project->lead_redirect_url);
        $this->assertEquals($factoryProject['reservation_redirect_url'], $project->reservation_redirect_url);
        $this->assertEquals($factoryProject['data_app_project_id'], $project->data_app_project_id);
    }
}
