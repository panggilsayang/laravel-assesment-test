<x-app-layout>
    @section('content')
        <div class="card row m-2 p-2">
            <div class="col-12">
                {{$dataTable->table()}}            
            </div>
        </div>
    @endsection

    @push('scripts')
        {{$dataTable->scripts()}}
    @endpush
</x-app-layout>
