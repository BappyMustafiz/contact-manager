<x-app-layout>
    <div class="py-6">
        <div class="container max-w-7xl">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">Contact List</div>
                        <button id="btnTrack" class="mr-3 btn btn-success">Track Klaviyo</button>
                        <form action="{{ route('track.klaviyo') }}" id="klaviyoForm" method="post" enctype="multipart/form-data">
                            @csrf
                        </form>
                        <div class="p-2 bd-highlight">
                            <a href="{{ route('contacts.create') }}" class="mr-3">Create New</a>
                            <a href="{{ route('export.contacts') }}" class="mr-3">Export</a>
                            <button id="btnImport" class="mr-3">Import</button>
                            <form action="{{ route('import.contacts') }}" id="form" method="post" enctype="multipart/form-data">
                                @csrf
                                <input class="d-none" type="file" id="file" name="file">
                            </form>
                        </div>
                      </div>
                </div>
            </div>
            <table class="table table-bordered mb-5 mt-3">
                <thead>
                    <tr class="table-success">
                        <th scope="col">First name</th>
                        <th scope="col">Last name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($contacts)
                        @foreach($contacts as $contact)
                            <tr>
                                <td>{{ $contact->first_name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>
                                    <a href="{{route('contacts.edit', $contact->id)}}">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
    
            <div class="d-flex justify-content-center">
                {!! $contacts->links() !!}
            </div>
        </div>
    </div>
</x-app-layout>
