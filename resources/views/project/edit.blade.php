<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Project') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-8 pt-6 pb-4">
                    <x-form :action="route('projects.update',$project)" method="put">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-forms.input-group name="name" :value="$project->name"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="url" :value="$project->url"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="mailchimp_api_key"
                                                     :value="$project->mailchimp_api_key"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="mailchimp_server"
                                                     :value="$project->mailchimp_server"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="mailchimp_list_id"
                                                     :value="$project->mailchimp_list_id"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="data_app_project_id"
                                                     :value="$project->data_app_project_id"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="reservation_cost" type="number"
                                                     :value="$project->reservation_cost"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="lead_redirect_url" type="url"
                                                     :value="$project->lead_redirect_url"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="reservation_redirect_url" type="url"
                                                     :value="$project->reservation_redirect_url"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="pixel_id" placeholder="Facebook Pixel id"
                                                     :value="$project->pixel_id"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="stripe_key" placeholder="Publishable API key pk_....."
                                                     :value="$project->stripe_key"
                                                     :required="false"></x-forms.input-group>
                            </div>
                            <div>
                                <x-forms.input-group name="stripe_secret" placeholder="Secret key sk_....."
                                                     :value="$project->stripe_secret"
                                                     :required="false"></x-forms.input-group>
                            </div>
                        </div>
                        <button class="flex-shrink-0 bg-teal-500 mt-4 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded float-right"
                                type="submit">
                            Update
                        </button>
                        <div class="clearfix"></div>
                    </x-form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>