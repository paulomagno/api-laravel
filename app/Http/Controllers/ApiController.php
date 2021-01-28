<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function __construct() {
        //$this->middleware('auth:sanctum');
    }


    // Cria um Tarefa
    public function createTodo(Request $request)
    {
        $array = ['error' => ''];

        $rules = [
            'title' => 'required|min:3'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $title = $request->input('title');

        $todo = new Todo();
        $todo->title = $title;
        $todo->save();

        $array['result'] = [
            'id'    => $todo->id,
            'title' => $todo->title,
        ];

        return $array;
    }

    // Lista todas as Tarefas
    public function readAllTodos()
    {
        $array['error'] = '';

        //$array['result'] = Todo::all();

        $todos = Todo::simplePaginate(2);

        $array['list'] = $todos->items();
        $array['current_page'] = $todos->currentPage();

        return $array;
    }


    // Lista uma tarefa
    public function readTodo($id)
    {
        $array['error'] = '';

        $todo = Todo::find($id);

        if($todo) {
            $array['result'] = $todo;
        } else {
            $array['error'] = 'A tarefa '.$id.' não existe';
        }


        return $array;
    }

    // Atualiza uma tarefa
    public function updateTodo($id,Request $request)
    {
        $array['error'] = '';

        $rules = [
            'title' => 'min:3',
            'done'  => 'boolean'
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $title = $request->input('title');
        $done  = $request->input('done');

        $todo = Todo::find($id);

        if($todo) {

            if($title) {
                $todo->title = $title;
            }

            if($done !== NULL) {
                $todo->done = $done;
            }

            $todo->save();

            $array['result'] = $todo;

        } else {
            $array['error'] = 'Tarefa '.$id. ' não existe';
        }



        return $array;
    }

    // Deleta uma tarefa
    public function deleteTodo($id)
    {
        $array['error'] = '';

        $todo = Todo::find($id);
        $todo->delete();

        return $array;
    }


}
