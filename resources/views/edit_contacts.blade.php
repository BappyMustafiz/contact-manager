<x-app-layout>
    <div class="py-6">
        <div class="container max-w-7xl">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex bd-highlight">
                        <div class="p-2 flex-grow-1 bd-highlight">Update Contact</div>
                        <div class="p-2 bd-highlight">
                            <a href="{{ route('contacts.index') }}">Contact List</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12">
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />
                    <form action="{{ route('contacts.update', $contact->id) }}" method="POST" data-parsley-validate data-parsley-focus="first">
                        @csrf
                        @method('put')
                        <div class="form-group row">
                            <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control-plaintext" id="first_name" name="first_name" value="{{ $contact->first_name }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="first_name" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control-plaintext" id="email" name="email" value="{{ $contact->email }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control-plaintext" id="phone" name="phone" value="{{ $contact->phone }}" placeholder="Enter valid E.164 phone number." required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="list_id" class="col-sm-2 col-form-label">List</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="list_id" value="{{ $contact->list_id }}" name="list_id" required>
                                    @if($lists)
                                        @foreach($lists as $list)
                                            @if($contact->list_id == $list['list_id'])
                                                <option value="{{ $list['list_id'] }}" selected>{{ $list['list_name'] }}</option>
                                            @else
                                                <option value="{{ $list['list_id'] }}">{{ $list['list_name'] }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>