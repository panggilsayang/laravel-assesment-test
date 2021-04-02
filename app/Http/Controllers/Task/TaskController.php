<?php

namespace App\Http\Controllers\Task;

use App\DataTables\TasksDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,TasksDataTable $tasksDataTable)
    {
        return $tasksDataTable->render('tasks.index');
        $user = Auth::user();
        $tasks = Task::query()->where(function($query) use($user) {
            return $query->where('user_id',$user->id)->orWhere('assignee_id',$user->id);
        })->with(['user','assignee'])->get();
        return $tasks;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $assigneeLists = User::assigneeLists($user)->get();
        $statusLists = TaskStatus::statusLists();
        return view('tasks.create',[
            'user_lists' => $assigneeLists,
            'status_lists' => $statusLists
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskCreateRequest $request)
    {
        $request->validated();
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $status = array_map(function ($value)
            {
                return ['status' => $value];
            },$request->status);
            $task = new Task([
                'title' => $request->title,
                'description' => $request->description,
                'due_dates' => $request->due_dates,
                'user_id' => $user->id,
                'assignee_id' => $request->assignee_id
            ]);
            $task->save();
            $task->status()->delete();
            $task->status()->createMany($status);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(),$th);
            return redirect()->back();
        }
        return redirect()->route('tasks.index')->with('message','Success create task');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $task->load('status');
        $user = Auth::user();
        $assigneeLists = User::assigneeLists($user)->get();
        $statusLists = TaskStatus::statusLists();
        return view('tasks.edit',[
            'task' => $task,
            'user_lists' => $assigneeLists,
            'status_lists' => $statusLists
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $request->validated();
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $status = array_map(function ($value)
            {
                return ['status' => $value];
            },$request->status);
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'due_dates' => $request->due_dates,
                'assignee_id' => $request->assignee_id
            ]);
            $task->status()->delete();
            $task->status()->createMany($status);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage(),$th);
            return redirect()->back();
        }
        return redirect()->route('tasks.index')->with('message','Success update task');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->back()->with('message','Success delete task');
    }
}