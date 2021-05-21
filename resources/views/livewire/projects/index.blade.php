<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight float-left">
            {{ __('Projects') }}
        </h2>
        <a href="{{route("projects.create")}}"
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md
                 text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo float-right
                  focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            New Project
        </a>
        <div class="clearfix"></div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                URL
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Data app project id
                            </th>
                            <th class="px-6 py-3 bg-gray-50 w-1/12"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($projects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    {{$project->name}}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    {{$project->url}}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    {{$project->data_app_project_id}}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                    <a href="{{route("projects.edit",$project)}}"
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             width="24" height="24" class="inline-block">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </a>
                                    <a onclick="deleteProject({{$project->id}})" href="#"
                                       class="text-red-700 hover:text-red-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             width="24" height="24" class="inline-block">
                                            <path fill-rule="evenodd"
                                                  d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-no-wrap text-center">
                                    No data found
                                </td>
                            </tr>
                    @endforelse

                    <!-- More rows... -->
                    </tbody>
                </table>
                {{$projects->links()}}
            </div>
        </div>
    </div>
</div>
@push("scripts")
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function deleteProject(projectId) {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: `Delete`,
                confirmButtonColor: `red`,
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("works")
                @this.delete(projectId);
                }
            })
        }
    </script>
@endpush