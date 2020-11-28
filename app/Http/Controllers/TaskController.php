<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use JWTAuth;

class TaskController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    } //end of the constructor method
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = $this->user->tasks()->get(["id", "title", "details", "created_by"])->toArray();

        return $tasks;
    } //end of the index method

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => "required",
            "details" => "required",
        ]);

        $task = new Task();

        $task->title = $request->title;
        $task->details = $request->details;

        if ($this->user->tasks()->save($task)) {
            return response()->json([
                "status" => true,
                "task" => $task,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, task could not be saved.",
            ], 500);
        }

    } //end of the store method

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            "title" => "required",
            "details" => "required",
        ]);

        $task->title = $request->title;
        $task->details = $request->details;

        if ($this->user->tasks()->save($task)) {
            return response()->json([
                "status" => true,
                "task" => $task,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, task could not be Updated.",
            ], 500);
        }

    } //end of the update method

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        if ($task->delete()) {
            return response()->json([
                "status" => true,
                "task" => $task,
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Ops, task could not be deleted.",

            ]);
        }
    } //end of the destroy method

} //end of the TaskController class