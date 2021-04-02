<div class="btn-group btn-xs btn-info" role="action">
    <button type="button" class="btn btn-xs btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Action
    </button>
    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
        <a class="dropdown-item" href="{{route('tasks.edit',['task' => $id])}}">Edit</a>
      
        <a class="dropdown-item" 
            href="{{ route('tasks.delete',['task' => $id]) }}"
            onclick="event.preventDefault();
            document.getElementById('delete-form').submit();">
            {{ __('Delete') }}
        </a>
        <form id="delete-form" action="{{ route('tasks.delete',['task' => $id]) }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>