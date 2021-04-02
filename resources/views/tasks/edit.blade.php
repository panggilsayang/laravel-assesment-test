<x-app-layout>
    @section('content')
        <div class="card m-2 p-2">
            <div class="row m-2 p-2">
                <div class="col-12">
                    <h3 class="text-right">Add Task</h3>
                </div>
                <div class="col-12">
                    <form action="{{route('tasks.update',['task' => $task->id])}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" value="{{$task->title}}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" rows="3" name="description" class="form-control" required>{{$task->description}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status[]" class="status form-control" multiple="multiple">
                                @foreach($status_lists as $key => $value)
                                    <option value="{{$key}}" {{in_array($key,$task->status->pluck('status')->toArray())?'selected':''}}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="assignee_id">Assignee</label>
                            <select name="assignee_id" class="assignee_id form-control">
                                @foreach($user_lists as $assignee)
                                    <option value="{{$assignee->id}}" {{$task->assignee_id==$assignee->id?'selected':''}}>{{$assignee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="due_dates">Due Dates</label>
                        <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
                            <input type="text" class="form-control" name="due_dates" value="{{$task->due_dates->format('Y-m-d')}}">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                        <br>
                        <button class="btn btn-info text-white" type="submit">Save</button>
                    </form>
                </div>
            </div>        
        </div>
    @endsection
    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
        <script>
            $(document).ready(function() {
                $('.assignee_id').select2();
                $('.status').select2();
                $('.datepicker').datepicker();
            });
        </script>
    @endpush
</x-app-layout>